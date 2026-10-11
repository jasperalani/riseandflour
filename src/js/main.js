/* =============================================================
   Rise & Flour — front-end behaviour
   Three independent pieces: the mobile nav panel, the
   reveal-on-scroll header, and the image carousel. Each no-ops
   cleanly if its markup isn't present, so the same file can be
   enqueued on every template.
   ============================================================= */
(function () {
  'use strict';

  /* -----------------------------------------------------------
     Mobile navigation
     Shared by both header variants — the burger and the panel
     are identical, only their colours differ (handled in CSS).
     ----------------------------------------------------------- */
  function initNav() {
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.querySelector('.site-nav');
    if (!toggle || !nav) return;

    function setOpen(open) {
      toggle.setAttribute('aria-expanded', String(open));
      nav.classList.toggle('is-open', open);
      document.body.classList.toggle('nav-open', open);
    }

    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    // Following a link closes the panel.
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setOpen(false);
    });

    // Leaving mobile width with the panel open would otherwise
    // strand `overflow:hidden` on the body.
    window.addEventListener('resize', function () {
      if (window.innerWidth > 640) setOpen(false);
    });
  }

  /* -----------------------------------------------------------
     Reveal-on-scroll header

     Scrolling down lets the header leave with the page. Once it's
     fully off-screen it swaps to `position: fixed` above the
     viewport (.is-floating) — the swap happens out of sight, so
     it can't be seen. From there, a small upward scroll pins it
     (.is-pinned) and it slides down; scrolling on again hides it.
     Back at the very top it rejoins the flow, where fixed and
     natural positions coincide, so the two headers merge.

     The stacked header occupies flow space, so while it floats
     the body gets matching padding — content doesn't shift when
     the header leaves or rejoins.
     ----------------------------------------------------------- */
  function initHeaderReveal() {
    var header = document.querySelector('.site-header');
    if (!header) return;

    // The overlay variant is absolute and takes no flow space.
    var inFlow = !header.classList.contains('site-header--overlay');

    var REVEAL = 5;   // px of upward scroll that pins the header
    var HIDE = 12;    // px of downward scroll that hides it again

    var floating = false;
    var lastY = Math.max(0, window.scrollY);
    var upRun = 0;
    var downRun = 0;
    var ticking = false;

    function enterFloating() {
      floating = true;
      if (inFlow) document.body.style.paddingTop = header.offsetHeight + 'px';
      header.classList.add('no-anim', 'is-floating');
      // Flush styles so the jump above the viewport doesn't animate.
      void header.offsetHeight;
      header.classList.remove('no-anim');
    }

    function exitFloating() {
      floating = false;
      header.classList.remove('is-floating', 'is-pinned');
      if (inFlow) document.body.style.paddingTop = '';
    }

    function update() {
      var y = Math.max(0, window.scrollY); // clamp iOS rubber-banding

      if (!floating) {
        // Engage only once the header has scrolled fully out of
        // view (plus a buffer), so the position swap is invisible.
        if (y > header.offsetHeight + 80) enterFloating();
      } else if (y <= 1) {
        // Back at the top — hand over to the in-flow header.
        exitFloating();
      } else {
        var dy = y - lastY;
        if (dy < 0) {
          upRun -= dy;
          downRun = 0;
          if (upRun >= REVEAL) header.classList.add('is-pinned');
        } else if (dy > 0) {
          downRun += dy;
          upRun = 0;
          if (downRun >= HIDE) header.classList.remove('is-pinned');
        }
      }

      lastY = y;
      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(update);
    }, { passive: true });

    // The floating header's height changes across breakpoints.
    window.addEventListener('resize', function () {
      if (floating && inFlow) {
        document.body.style.paddingTop = header.offsetHeight + 'px';
      }
    });

    update();
  }

  /* -----------------------------------------------------------
     Carousel

     All state is one index. Each slide gets --rel (how many
     places it sits to the right of the active slide, floored at
     zero) plus .is-active / .is-passed, and the CSS turns those
     into the motion: upcoming slides queue to the right, the
     active one is focused, passed ones wipe closed in place.
     Captions carry the same two classes and roll vertically in
     the direction of travel. Geometry lives entirely in the CSS
     custom properties, so nothing here needs measuring.
     ----------------------------------------------------------- */
  function initCarousel(root) {
    var stage = root.querySelector('[data-carousel-stage]');
    var slides = Array.prototype.slice.call(root.querySelectorAll('[data-carousel-slide]'));
    var captions = Array.prototype.slice.call(root.querySelectorAll('[data-carousel-caption]'));
    var dashes = Array.prototype.slice.call(root.querySelectorAll('[data-carousel-go]'));
    var prev = root.querySelector('[data-carousel-prev]');
    var next = root.querySelector('[data-carousel-next]');

    var total = slides.length;
    if (!stage || !total) return;

    var index = 0;

    function mark(el, rel) {
      el.classList.toggle('is-active', rel === 0);
      el.classList.toggle('is-passed', rel < 0);
      el.setAttribute('aria-hidden', rel === 0 ? 'false' : 'true');
    }

    function render() {
      slides.forEach(function (slide, i) {
        var rel = i - index;
        slide.style.setProperty('--rel', String(Math.max(rel, 0)));
        mark(slide, rel);
      });

      captions.forEach(function (caption, i) { mark(caption, i - index); });

      dashes.forEach(function (dash, i) {
        dash.classList.toggle('is-active', i === index);
        if (i === index) dash.setAttribute('aria-current', 'true');
        else dash.removeAttribute('aria-current');
      });

      // aria-disabled, not disabled: disabling the focused button would
      // drop keyboard focus to <body> and kill arrow-key navigation.
      // goTo() clamps, so a click on an aria-disabled button is a no-op.
      if (prev) prev.setAttribute('aria-disabled', index <= 0 ? 'true' : 'false');
      if (next) next.setAttribute('aria-disabled', index >= total - 1 ? 'true' : 'false');
    }

    function goTo(i) {
      index = Math.max(0, Math.min(i, total - 1));
      render();
    }

    if (prev) prev.addEventListener('click', function () { goTo(index - 1); });
    if (next) next.addEventListener('click', function () { goTo(index + 1); });

    dashes.forEach(function (dash) {
      dash.addEventListener('click', function () {
        goTo(parseInt(dash.getAttribute('data-carousel-go'), 10) || 0);
      });
    });

    // Arrow keys work while focus is anywhere inside the block. Modified
    // arrows are browser shortcuts (Alt+Left = back) and pass through.
    root.addEventListener('keydown', function (e) {
      if (e.altKey || e.ctrlKey || e.metaKey || e.defaultPrevented) return;
      if (e.key === 'ArrowRight') {
        e.preventDefault();
        goTo(index + 1);
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        goTo(index - 1);
      }
    });

    render();

    if (total < 2) return;

    /* --- Swipe ----------------------------------------------- */
    // A horizontal swipe past the threshold moves one slide on
    // release; the CSS transition does the rest. Nothing tracks
    // the pointer mid-gesture — the wipe-and-slide choreography
    // doesn't scrub well, and a clean commit reads better.

    var SWIPE = 40;
    var pointerId = null;
    var startX = 0;
    var startY = 0;
    var axis = null;       // 'x' once we own the gesture, 'y' if the page does

    stage.classList.add('is-swipeable');

    stage.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'mouse' && e.button !== 0) return;
      pointerId = e.pointerId;
      axis = null;
      startX = e.clientX;
      startY = e.clientY;
    });

    stage.addEventListener('pointermove', function (e) {
      if (pointerId === null || e.pointerId !== pointerId || axis !== null) return;

      var mx = e.clientX - startX;
      var my = e.clientY - startY;

      // Wait for a clear direction before committing, so a vertical
      // swipe scrolls the page instead of nudging the carousel.
      if (Math.abs(mx) < 6 && Math.abs(my) < 6) return;

      axis = Math.abs(mx) > Math.abs(my) ? 'x' : 'y';
      if (axis === 'x') {
        try { stage.setPointerCapture(pointerId); } catch (err) {}
      }
    });

    function endSwipe(e, cancelled) {
      if (pointerId === null || e.pointerId !== pointerId) return;

      var dx = e.clientX - startX;
      var owned = axis === 'x';

      if (owned) {
        try { stage.releasePointerCapture(pointerId); } catch (err) {}
      }
      pointerId = null;
      axis = null;

      if (cancelled) return;

      // A press that never picked an axis is a click or tap. On the
      // peeking slide that means "show me that one"; elsewhere it's
      // nothing. Handled here rather than with a click listener so a
      // swipe that ends over the next slide can't double-fire.
      if (!owned) {
        var hit = e.target.closest ? e.target.closest('[data-carousel-slide]') : null;
        var i = hit ? slides.indexOf(hit) : -1;
        if (i > index) goTo(i);
        return;
      }

      if (Math.abs(dx) < SWIPE) return;
      goTo(index + (dx < 0 ? 1 : -1));
    }

    stage.addEventListener('pointerup', function (e) { endSwipe(e, false); });
    stage.addEventListener('pointercancel', function (e) { endSwipe(e, true); });

    // Stops the browser's native image-drag ghost interrupting a swipe.
    stage.addEventListener('dragstart', function (e) { e.preventDefault(); });

    /* --- Autoplay -------------------------------------------- */
    // data-carousel-autoplay carries the delay in ms (absent = off).
    // It advances until the end, snaps back to the start without the
    // travel animation, and goes round again. Any manual navigation
    // stops it for good; hovering or leaving the viewport pauses it;
    // visitors who prefer reduced motion never see it run.

    var autoMs = parseInt(root.getAttribute('data-carousel-autoplay'), 10) || 0;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!autoMs || reduceMotion) return;

    var timer = null;
    var inView = true;
    var liveRegion = root.querySelector('[aria-live]');

    // Screen readers shouldn't get a new caption read to them every few
    // seconds; announcements come back once autoplay stops.
    function setLive(polite) {
      if (liveRegion) liveRegion.setAttribute('aria-live', polite ? 'polite' : 'off');
    }

    function snapTo(i) {
      root.classList.add('carousel--no-anim');
      goTo(i);
      void stage.offsetHeight; // flush styles so the jump doesn't animate
      root.classList.remove('carousel--no-anim');
    }

    function play() {
      if (timer !== null) return;
      setLive(false);
      timer = setInterval(function () {
        if (document.hidden || !inView) return;
        if (index >= total - 1) snapTo(0);
        else goTo(index + 1);
      }, autoMs);
    }

    function pause() {
      if (timer === null) return;
      clearInterval(timer);
      timer = null;
    }

    // The visitor has taken the wheel — don't fight them.
    function stopForGood() {
      pause();
      setLive(true);
      root.removeEventListener('pointerdown', stopForGood, true);
      root.removeEventListener('keydown', stopForGood, true);
      root.removeEventListener('focusin', stopForGood);
      root.removeEventListener('mouseenter', pause);
      root.removeEventListener('mouseleave', play);
    }

    // Capture phase, so these run whatever the target — arrows, dashes,
    // a swipe on the stage, a tap on the peeking slide.
    root.addEventListener('pointerdown', stopForGood, true);
    root.addEventListener('keydown', stopForGood, true);
    root.addEventListener('focusin', stopForGood);

    root.addEventListener('mouseenter', pause);
    root.addEventListener('mouseleave', play);

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        inView = entries[0].isIntersecting;
      }, { threshold: 0.25 }).observe(root);
    }

    play();
  }

  /* -----------------------------------------------------------
     Events: "Show more" past events

     The block renders the first batch; each click fetches the
     next from the theme's REST endpoint (already-rendered rows,
     same template part) and appends it. The button carries its
     own endpoint, offset and batch size, so nothing is measured
     or configured here. Falls back to the error label on a
     failed request and lets the user try again.
     ----------------------------------------------------------- */
  function initEventsMore(button) {
    var list = button.closest('.block-events').querySelector('[data-events-past]');
    var count = button.parentNode.querySelector('[data-events-count]');
    if (!list) return;

    var endpoint = button.getAttribute('data-endpoint');
    var offset = parseInt(button.getAttribute('data-offset'), 10) || 0;
    var batch = parseInt(button.getAttribute('data-batch'), 10) || 3;
    var total = parseInt(button.getAttribute('data-total'), 10) || 0;
    var busy = false;

    function setLabel(key) {
      button.textContent = button.getAttribute('data-' + key) || button.textContent;
    }

    button.addEventListener('click', function () {
      if (busy) return;
      busy = true;
      button.setAttribute('aria-busy', 'true');
      setLabel('loading');

      var url = endpoint + (endpoint.indexOf('?') === -1 ? '?' : '&') +
        'which=past&offset=' + offset + '&limit=' + batch;

      fetch(url, { credentials: 'same-origin' })
        .then(function (res) {
          if (!res.ok) throw new Error(res.status);
          return res.json();
        })
        .then(function (data) {
          var tmp = document.createElement('ul');
          tmp.innerHTML = data.html || '';
          var first = tmp.firstElementChild;
          while (tmp.firstElementChild) list.appendChild(tmp.firstElementChild);

          offset += data.count || 0;
          total = data.total || total;
          if (count) {
            count.textContent = count.textContent.replace(/\d+/, String(offset)).replace(/(\d+)(\D*)$/, total + '$2');
          }

          // Move focus to the first new row's link so keyboard and screen
          // reader users land where the new content starts.
          var link = first && first.querySelector('.event-card__title a');
          if (link) link.focus({ preventScroll: true });

          if (!data.has_more) {
            button.parentNode.removeChild(button);
          } else {
            setLabel('label');
          }
        })
        .catch(function () {
          setLabel('error');
        })
        .then(function () {
          busy = false;
          button.removeAttribute('aria-busy');
        });
    });
  }

  function init() {
    initNav();
    initHeaderReveal();
    document.querySelectorAll('[data-carousel]').forEach(initCarousel);
    document.querySelectorAll('[data-events-more]').forEach(initEventsMore);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
