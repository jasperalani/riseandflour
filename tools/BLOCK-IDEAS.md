## Block ideas

Grounded in what the site actually needs. The theme started with five blocks  
(Hero, Text, Image + Text, Image, Carousel); **Tier 1 is now built** (see the  
✅ DONE notes on each — Menu, Events, Location, Contact form, and hours from  
Site Details), which replaces the old theme's hard-coded page templates  
(`menu.php`, `archive-events.php`, `location.php`, `reservations.php`,  
`ourfood.php`, `careers.php` at commit `63033b0`). Photo grid is the one  
Tier 1 item still open. Tiers 2 and 3 are nice-to-have; the "- yes" notes  
are the café's picks. Deferred work lives in `TODO.md`.

Constraints every idea respects: café not bakery, **no ecommerce**, flat  
colours, near-square corners, modest 150–200ms motion, Montserrat caps for  
headings/labels, EB Garamond body, terracotta `.amp`. Core Magic is allowed  
for **dish/product names only** — the Menu block is the one place it can  
legitimately appear in body content.

## Tier 1 — needed to rebuild the empty pages

### 1\. Menu - yes — ✅ DONE (22 Aug 2026)

Built as the **structured** version (`acf/rf-menu`): sections → dishes with  
name in Core Magic, price, description, vegetarian chip, "Add" extras and a  
note; hours panel (falls back to Site Details) and closing notes; optional  
PDF download. Seeded from the print menu. Plus **Site Details → Menu  
import**: upload the menu PDF, Claude reads it, review the diff, apply.  
Images on the menu page are in `TODO.md`.

The old site showed the menu as a single uploaded image. Two ways to go, and  
a block could support both:

*   **Image menu** (what they do today): `menu_image`, optional `pdf` for a  
    "Download the menu" link. Fastest for the café — they already design the  
    menu as a print PDF.
*   **Structured menu**: `sections` repeater (section heading e.g. _Breakfast_,  
    _Lunch_, _Cakes_, _Drinks_) → `items` repeater (`name`, `description`,  
    `price`, `tags` — v / vg / gf). Dish names in Core Magic, prices  
    right-aligned with a dotted/hairline leader, tags as tiny caps chips.  
    Better for accessibility and search than an image, and it's the one block  
    where the brand font earns its place.

Fields: `mode` (image / list), then the relevant set. Mention in the  
README that prices are text, not a shop — no ecommerce.

### 2\. Events list - yes — ✅ DONE (22 Aug 2026)

Built as the **CPT** version: `rf_event` post type (dates, location, price,  
capacity, eyebrow/subtitle, "On the night" lines, booking link) with the  
Events block (`acf/rf-events`, upcoming then past) and `single-rf_event.php`  
following the Claude Design "Supper Club" page; mobile list layout from the  
Claude Design mobile screen. Seeded with the old site's past events. Open  
in `TODO.md`: booking functionality, show-more, calendar selector.

The old theme had an `events` custom post type with an ACF `date_time` and  
split upcoming vs past ("This event has passed"). Two options:

*   **Keep a CPT** and add an **Events block** that queries it: `show`  
    (upcoming / past / both), `limit`, `layout` (list / cards). Each row:  
    date block (day big, month caps), title, excerpt, image, link. Upcoming  
    first, then a muted "Past events" run. Needs the CPT registering in  
    `inc/` plus a field group with `date_time`, `end_time`, `location`,  
    `price` (text), `booking_link`.
*   **Repeater-only block** with no CPT: `events` repeater (date, title,  
    blurb, image, link). Simpler, but past events have to be deleted by hand  
    and there's no single-event page. Fine if they run 3–4 events a year.

Recommend the CPT — it's what they had, and the automatic upcoming/past  
split is the feature they'll miss otherwise.

### 3\. Location / Find us - yes — ✅ DONE (22 Aug 2026)

Built (`acf/rf-location`): terracotta-labelled Address / Hours / Getting  
here / Contact beside a Google Map in a terracotta frame; address, hours,  
phone and email all fall back to Site Details; map accepts the embed URL or  
the whole iframe (Google/OSM only); directions link; map left/right.  
Seeded with the café's real pin. Needs a Terms of Use page for Google Maps  
— in `TODO.md`.

Replaces `location.php`. Two columns: address + phone + email + opening  
hours on one side, map on the other. Fields: `map_embed` (the Google Maps  
embed URL — the old one is in `location.php`), `heading`, `show_hours`  
(pulls from Site Details so it's never duplicated), `note` (e.g. parking,  
"step-free access"), `directions_link`. Image-side/text-side toggle like  
Image + Text. Map iframe gets `loading="lazy"` and a flat cream border.

### 4\. Opening hours - yes — ✅ DONE (22 Aug 2026)

The hours live in **Site Details** and are read by the footer, the Location  
block, the Menu block's hours panel, the Contact block and now the  
**Opening hours block** (`acf/rf-hours`: eyebrow, heading, stacked or inline  
layout, centre/left, optional note; its own hours field only as an override).  
The structured day/time repeater is done too: hours are rows on Site Details,  
with JSON-LD for Google and an "Open now" line.

These are the opening hours:  
Monday - Friday, 8:30 am – 3 pm  
Saturday, 9 am – 3 pm  
Sunday, 9 am – 2:30 pm

Small, reusable, pulls from the **Site Details** options page — no fields  
except `heading` and `layout` (stacked / inline). The footer already shows  
hours; this puts them on Location and possibly Home without re-typing.  
Optional ACF `hours` repeater on Site Details (`days`, `times`) replacing  
the free-text textarea, so one source feeds footer, block and JSON-LD.

### 5\. Contact / enquiry form - yes - do we need a wordpress plugin for this? — ✅ DONE (22 Aug 2026)

No form plugin needed — built in the theme (`acf/rf-contact` +  
`inc/contact.php`): details column from Site Details plus extra notes, form  
(name, email, subject dropdown, message) in a dotted terracotta panel,  
nonce + honeypot + fill-timer, sends via `wp_mail()`, redirects to a  
thank-you state. The only plugin involved is for *delivery*: FluentSMTP  
routes `wp_mail()` through Microsoft 365 (setup steps in `TODO.md`).  
Contact page created. Fifield Room hire / careers can reuse it with a  
different subject list or recipient.

Replaces the hand-rolled `form_send_email()` in `reservations.php` and  
`careers.php`. Fields: `heading`, `intro`, `recipient` (email), `subject`,  
`fields` (which inputs to show: name, email, phone, date, party size,  
message), `success_message`. Honeypot + nonce, `wp_mail()`. Or, if they'd  
rather not own form code, a thin wrapper block around a Contact Form 7 /  
WPForms shortcode with the theme's styling. Used for **Fifield Room hire**  
and **careers**.

### 6\. Photo grid - yes

Replaces `ourfood.php`, which split the page content on `/&gt;` and shuffled  
the images (!). Fields: `images` (gallery), `columns` (2 / 3 / 4),  
`aspect` (square / 3:2 / natural masonry), `shuffle` toggle, optional  
`lightbox`. Also useful on About.

## Tier 2 — strengthens Home / About

### 7\. Feature trio / "three things" - yes

Three (or 2–4) short columns: icon or small image, caps label, a line of  
body. "Coffee from X · Everything baked in-house · Dog friendly." Fields:  
`items` repeater (`image`, `label`, `text`, `link`), `columns`. The classic  
below-the-hero block the design doesn't have yet.

### 8\. Quote / testimonial - yes, also think about a reviews block that shows off something like this: [https://oxinabox.co.uk/review-brunch-at-the-charming-rise-flour-artisan-cafe-in-milton-under-wychwood-where-youll-find-some-really-interesting-food-with-a-kiwi-twist/](https://oxinabox.co.uk/review-brunch-at-the-charming-rise-flour-artisan-cafe-in-milton-under-wychwood-where-youll-find-some-really-interesting-food-with-a-kiwi-twist/) (we can find more)

Single large EB Garamond italic quote (the brand already reserves italic for  
quotes), attribution in caps, optional ampersand flourish. Fields: `quote`,  
`attribution`, `source` (Google / Tripadvisor / press), `align`. A  
`quotes` repeater variant could fade between several on a slow timer.

### 9\. Statement / brand line - yes

A full-bleed cream-on-ink band with one sentence set big, the `&amp;` in  
terracotta, optional stamp logo. Basically Hero without the image — for  
page breaks and the end of About. Fields: `text`, `show_stamp`, `tone`  
(ink / cream / terracotta).

### 10\. Call to action - yes

`heading`, `text`, `button_text`, `button_url`, `style` (outline / solid),  
`tone`. Ends a page ("Book the Fifield Room", "See what's on"). Tiny, but  
every page currently has no way to end with a button.

### 11\. Team / people  - yes - focused on Joanna Alani the founder here is linkedin maybe some good info there: https://www.linkedin.com/in/joanna-alani-28781616/

`people` repeater (`photo`, `name`, `role`, `blurb`). Round crops, caps  
roles. For About.

### 12\. Timeline / "our story" - yes i need to manually write the timeline for you but yes

Vertical hairline with year markers: `entries` repeater (`year`, `title`,  
`text`, `image`). Est. line lives in Site Details already, so the first  
entry could default to it.

### 13\. Image pair / diptych - yes 

Two images side by side with a shared caption, optional offset (one sits  
lower) for editorial rhythm. Fields: `left`, `right`, `caption`, `offset`.  
Cheap to build on top of the Image block and gives About some variety  
beyond alternating Image + Text.

### 14\. Instagram strip - yes

Row of 4–6 latest posts. Needs either the official Instagram Basic Display  
API (token maintenance — a pain) or a `images` repeater the café updates by  
hand with a "Follow us" link. Recommend the manual version; the API one  
rots.

## Tier 3 — utilities and small wins

### 15\. Notice / announcement bar - yes good

Not a page block — a Site Details field (`notice_text`, `notice_link`,  
`notice_on`) rendered by `header.php`. "Closed Monday 25th for the bank  
holiday." The single most-requested thing on café sites.

### 16\. FAQ / accordion - yes i like accordian and think of good questions i like these Room hire, allergens, dogs, parking

`items` repeater (`question`, `answer`). `<details>`/`<summary>` so it's  
accessible with no JS. Room hire, allergens, dogs, parking.

### 17\. Download / document - possibly, low priority

`file`, `label`, `description`. Menu PDF, room-hire terms, allergen sheet.

### 18\. Divider / ornament - yes good

Hairline with the `&amp;` or monogram centred. Fields: `style` (line / amp /  
monogram). A decorative escape hatch so editors stop using Spacer + HTML.

### 19\. Map only - ok, low priority

If Location (3) feels too opinionated, a bare `map_embed` block with a  
caption.

### 20\. Careers / positions list - yes low priority

`intro`, `positions` repeater (`title`, `hours`, `description`), `apply_email`.  
Replaces the hard-coded `<ul>` in `careers.php`. Could just be Text +  
Contact form instead — borderline.

## Status

Done: **Menu**, **Events** (CPT + list + single), **Location**, **Contact  
form**, **Opening hours**, and hours shared from Site Details. Decisions that were pending are  
settled: Events = CPT, Menu = structured (+ PDF import), Forms = own code  
with FluentSMTP for delivery.

Next, in the order that pays off fastest:

1.  **Photo grid** (6) — the last Tier 1 item; also wanted on About.
2.  **Call to action** (10), **Quote / reviews** (8), **Feature trio** (7) —  
    quick, and Home/About immediately look richer.
3.  **Notice bar** (15), **FAQ accordion** (16), **Divider** (18) — small  
    utilities the café asked for.
4.  **Team** (11), **Timeline** (12, needs the café's copy), **Statement**  
    (9), **Diptych** (13), **Instagram strip** (14).
5.  Low priority: Download (17), Map only (19), Careers (20).

Still to decide: whether to move footer hours to a structured repeater  
(affects 4 and `footer.php`, plus a snapshot refresh).

Adding a block is: folder in `src/blocks/<name>/` (block.json + render.php),  
a template part in `src/template-parts/blocks/`, a field group in  
`src/inc/acf-fields.php`, SCSS in `src/scss/blocks/`, and a row in the  
README table. `rise_and_flour_allowed_blocks()` whitelists the editor, so  
each new block must be added there too.