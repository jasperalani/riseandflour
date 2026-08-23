# To do

Things worth doing that aren't high enough priority right now. Add to the
list as they come up; move an item out when it's done. (Block ideas live in
`BLOCK-IDEAS.md`.)

## Site-wide

- **Finish setting up FluentSMTP with the Microsoft 365 mailer.** The
  Contact form sends through `wp_mail()`, which FluentSMTP takes over; until
  the mailer is authenticated, submissions show "couldn't be sent". Needs a
  Microsoft 365 **Global Administrator** (or Application Administrator)
  account — being logged into the mailbox isn't enough.

  1. Sign in at https://entra.microsoft.com as the admin. *Identity →
     Applications → App registrations → New registration*. Name it
     "Rise & Flour website mail"; account type **single tenant**; Redirect
     URI platform **Web**, value = the Redirect URL FluentSMTP shows next to
     its Client ID / Secret fields (copy it exactly). Register.
  2. *Overview* → **Application (client) ID** → FluentSMTP's *Application
     Client ID*.
  3. *Certificates & secrets → New client secret*. Max expiry is 24 months —
     put a reminder in the diary, mail stops when it lapses. Copy the
     **Value** column (shown once), not "Secret ID" → *Application Client
     Secret*.
  4. *API permissions → Add → Microsoft Graph → Delegated*: `Mail.Send` and
     `offline_access` (check FluentSMTP's own doc for its exact list), then
     **Grant admin consent**.
  5. In FluentSMTP paste both values, set From Email to the sending
     mailbox, save, then click its **Authenticate** button and sign in as
     that mailbox (e.g. contact@riseandflour.co.uk) and accept consent.
  6. FluentSMTP → *Email Test* to hello@/contact@, then submit the Contact
     page; it should show the thank-you screen.

  Line-up on our side: the From address must be the mailbox authenticated
  in step 5 (the theme sets no From header, and Reply-To is the visitor).
  Messages go to **Site Details → Email** (currently hello@) unless the
  block's *Send messages to* field is set — if the real inbox is contact@,
  change Site Details, which also updates the footer, Location and Contact
  pages.

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
  requirements" — is confirming exactly this.) ✅ Written 22 Aug 2026
  (Gutenberg markup supplied in chat) with the Google Maps clause, and the
  Privacy Policy already covers the map's cookies. To do: create a *Terms of
  Use* page, paste the markup into its Code editor, publish, and link it
  from the footer beside Privacy Policy.

- **Custom dropdown for the Contact form's Subject field.** The form uses
  a native `<select>` styled with our chevron; the open list is still the
  browser's own. Replace it with a custom dropdown in the brand style
  (cream panel, hairline, caps options, terracotta hover) — a button +
  listbox with full keyboard support (arrow keys, type-ahead, Escape),
  `aria-expanded`/`aria-activedescendant`, and the native `<select>` kept
  underneath (visually hidden) so the form still posts and works without
  JS. Touches `template-parts/blocks/contact.php`, `_contact.scss`,
  `main.js`.

- **Homepage header nav: bigger font size** (added 23 Aug 2026). The
  overlay nav's links (13px caps, `.site-nav a` in `_header.scss`) read
  small over the full-page hero. Decide whether the bump is overlay-only
  or both header variants — they share `.site-nav`.

- **"Sample menu" title on the Menu page** (added 23 Aug 2026). Somewhere
  on the page (heading, note or a line near the PDF link) so visitors know
  the menu changes and what's shown is representative. Probably just
  editor content / block fields rather than theme work — check the Menu
  block's `heading` / `intro` / `notes` fields first.

- **Gift voucher page + Square** (added 23 Aug 2026). A vouchers page and
  a way to buy: Square eGift Cards has a hosted storefront page — the
  no-code route is an image-text/CTA block linking out to it (works with
  the no-ecommerce rule since payment happens on Square, not the site).
  Anything deeper (embedded checkout, Square Web Payments SDK) is real
  ecommerce and needs a decision to relax that rule. Needs the café's
  Square account to have eGift Cards enabled to get the storefront URL.

- **Fifield Room page** (added 23 Aug 2026). The private-hire back room
  (named for Joanna's village). Page from existing blocks: hero or Image
  block of the room, Text intro, Gallery, capacity/pricing details, and a
  contact/CTA for enquiries (the Contact block's `subjects` already lists
  "Events & private hire"). Link it from About and the events/supper-club
  copy once live.

- **Careers / jobs page and footer link** (added 23 Aug 2026). The old
  theme had `careers.php`; the new site has no jobs page yet. Needs: a
  Careers page built from existing blocks (Text intro + the Careers /
  positions list block, BLOCK-IDEAS #20, or a Text block until that
  exists) with an email-to-apply line, and a "Careers" link in the full
  front-page footer — probably beside Privacy Policy / Terms of Use in the
  baseline, or a `careers_page` post_object on Site Details → Legal pages
  (rename the tab "Pages") so the link only prints once the page is
  published, like Terms of Use.

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

- **Privacy Policy — final checks before it goes live.** Rewritten on
  22 Aug 2026 for the café's real setup (contact form → Microsoft 365,
  SiteGround hosting, Google Maps embed, events/private-hire enquiries) and
  pasted into the Privacy Policy page; rights requests go to
  `contact@riseandflour.co.uk`, no `privacy@` mailbox or DSAR form needed.
  Still to do: make Site Details → Email match `contact@` (it says
  `hello@`), optionally add the Companies House number after "Rise and
  Flour Ltd" in *Who we are*, confirm the two-year retention line, publish
  the page, and link it from the footer alongside the Terms of Use.
- **Footer links to Privacy Policy and Terms of Use.** ✅ Added to the
  footer baseline (both variants) on 22 Aug 2026. Each link appears only
  once its page is published: Privacy Policy comes from *Settings → Privacy*
  (make sure that setting points at the policy page), Terms of Use from the
  page picked under *Site Details → Legal pages* (falls back to the slug
  `terms-of-use`). A draft Terms of Use page with the full text is already
  created — review and publish it.

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
- **"Show more" on the Events list.** ✅ Done 22 Aug 2026 for past events:
  the block shows the first batch (field *Past events per batch*, default
  3) and a *Show more* button appends the next batch from
  `GET /wp-json/rf/v1/events`, with a "Showing 3 of 9" count. Upcoming
  events are always shown in full (capped only by *Upcoming limit*).
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

- ✅ **Structured opening hours** (22 Aug 2026): Site Details → Hours holds
  groups of day-range rows; footer, Location, Contact, Menu and the Opening
  hours block all read from it; JSON-LD `openingHoursSpecification` is
  emitted in `<head>`; an "Open now · closes 3pm" line is computed in the
  browser. Still free text: *Exceptions* (e.g. "Closed 24–27 December").

## Housekeeping

- `preview/` is empty: either rebuild `preview/index.html` from the theme
  markup or drop the "Static preview" section from the README.
- Replace `screenshot.png` with a real theme screenshot.
