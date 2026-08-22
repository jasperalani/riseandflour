# To do

Things worth doing that aren't high enough priority right now. Add to the
list as they come up; move an item out when it's done. (Block ideas live in
`BLOCK-IDEAS.md`.)

## Site-wide

- **Google Analytics support.** A `ga_measurement_id` field on Site Details
  (G-XXXXXXX); when set, `header.php` prints the gtag.js snippet via
  `wp_head`, skipped for logged-in users so admin visits don't count. Keep
  it a single field rather than a plugin — nothing else to configure. Worth
  a cookie/consent decision first: GA4 sets cookies, so a small consent
  notice (or switching to a cookieless option like Plausible) may be needed
  for UK/EU visitors.

- **Terms of Use page.** Required by the Google Maps Platform API Terms of
  Service because the Location block embeds a Google Map. The Terms of Use
  (1) must be publicly available, and (2) must explicitly state that by
  using the site, users are bound by Google's Terms of Service. Without a
  Terms of Use meeting these requirements the Google Maps Platform APIs
  should not be used. (The checkbox on the Maps Platform sign-up — "By
  checking this box, I am confirming that I understand Google's
  requirements" — is confirming exactly this.) Plan: a *Terms of Use* page
  built from Text blocks, linked from the footer beside Privacy Policy, with
  a paragraph along the lines of "This site uses Google Maps. By using it
  you agree to be bound by Google's Terms of Service
  (https://www.google.com/intl/en/policies/terms/)". The Privacy Policy
  should mention Google Maps too, since the embed sets cookies.

## Menu

- **Add images to the menu page.** Options, roughly in order of effort:
  - a single hero/banner photo above the Menu block (already possible: put
    an Image block before it — just needs a good photo);
  - an optional `image` per section shown beside the section title;
  - an optional small thumbnail per dish (square crop, left of the name),
    with the block switching to a one-column "card" layout when any dish
    has one — the current two-column text layout is too narrow for photos;
  - a photo strip between sections using the Carousel or a Photo grid block.
  The per-dish thumbnail is the one the café would most likely want, and
  the one that needs a design pass.
- Menu import: let the café choose *which* changes to apply (tick boxes on
  the review screen) rather than all-or-nothing.
- Menu import: read a photo of the printed menu (JPEG/PNG) as well as a PDF.
- Drinks / cakes sections once the café has a printed version of them.

- **Privacy Policy: check the contact-us link.** The page is still the
  WordPress default draft; make sure its "contact us" link points at the
  right place (the Location page, or the email in Site Details) before it's
  published and linked from the footer.
- **Privacy Policy: check the contact email.** The policy answers "What is
  the email address users should contact in case they have questions about
  your policy?" — make sure it's the real address (and the same one as in
  Site Details), not a placeholder.

## Events

- **Event booking functionality.** Today booking is just a link (the event's
  *Booking link* field — a mailto: or an external page such as Eventbrite).
  Proper booking would mean: a form on the event page (name, email, number
  of places, dietary notes), places counted against the event's `capacity`
  so it shows "3 places left" / "Sold out", a confirmation email to the
  guest and a notification to the café, and a bookings list in wp-admin
  under the event. No payment on-site (brand rule: no ecommerce) — keep the
  "no payment until the night" model, or hand off to Eventbrite/Stripe
  Payment Links if money has to be taken up front. Decide before building:
  own code (a `rf_booking` post type + `wp_mail`, like the Contact form idea
  in BLOCK-IDEAS.md) vs a plugin.
- **"Email to reserve" button must change when booking is added.** The
  button on the single event page (`single-rf_event.php`, fields *Booking
  link* / *Button text* / *Line under the button*) currently sends people to
  email. Once booking exists it should open the booking form instead, and
  the line beneath ("We reply within a day…") should be rewritten to match.
  Update the seeded Supper Club No. 12 event too.
- **"Show more" on the Events list.** Once there are lots of events the
  page gets long — add a *Show more* button that loads the next batch
  rather than listing everything. The block's `limit` field already caps
  each list; the missing piece is a button beneath the upcoming and past
  lists that fetches the next page (a small REST endpoint or `admin-ajax`
  returning rendered rows from `rise_and_flour_events()`, appended by
  `main.js`), hiding itself when there's nothing left. Past events are the
  list that will actually grow, so it could apply there only, with upcoming
  always shown in full. Touches `template-parts/blocks/events.php`,
  `inc/events.php`, `main.js`, `_events.scss`.
- **Calendar selector on the Events page.** Some way to browse events by
  date rather than one long list. Options, roughly in order of effort:
  - a month picker (a row of month/year links above the list, only months
    that have events) that filters the list in place with a little JS —
    no page reload, no plugin;
  - a mini calendar grid (one month at a time, days with events marked in
    terracotta, click a day to jump to/filter that event) — more work, and
    only worth it once there are enough events to fill one;
  - an "Add to calendar" link per event (.ics download / Google Calendar
    link) — not a selector, but the thing people usually want from an
    events calendar and cheap to add to `single-rf_event.php`.
  The events block already has the data it needs
  (`rise_and_flour_events()`), so this is front-end work on
  `template-parts/blocks/events.php` + `_events.scss` + `main.js`.

## Site Details

- Move opening hours to a structured repeater (day ranges + times) so the
  footer, the Menu block's hours panel and a future Opening Hours block all
  read from one place, and JSON-LD `openingHours` can be emitted for Google.

## Housekeeping

- `preview/` is empty: either rebuild `preview/index.html` from the theme
  markup or drop the "Static preview" section from the README.
- Replace `screenshot.png` with a real theme screenshot.
