# Rise & Flour

The Rise & Flour café site, built as a WordPress theme. The theme lives in
`src/` and is the redesign; source of truth for the design is `design/`
(Claude Design export) plus the Rise & Flour Design System tokens.

```
src/          the WordPress theme (rise-and-flour) — this is what deploys
preview/      static HTML preview — no WordPress needed
tools/        dev-only scripts (seed.php, snapshot.php), kept out of the theme
design/       Claude Design export — source of truth for the design
assets/       original brand assets: fonts, logo sources, illustrations
config/       wp-htaccess (pretty permalinks) + php-uploads.ini (upload cap),
              both mounted in by compose
compose.yml   local WordPress pointed at src/
```

## Editor setup

VS Code's PHP Tools doesn't know WordPress/ACF functions out of the box —
`.stubs/` holds stub declarations it indexes to fix the "unknown function"
warnings. The folder is gitignored; recreate it on a fresh clone with:

```bash
mkdir -p .stubs
curl -sL -o .stubs/wordpress-stubs.php https://raw.githubusercontent.com/php-stubs/wordpress-stubs/master/wordpress-stubs.php
curl -sL -o .stubs/acf-stubs.php https://raw.githubusercontent.com/php-stubs/acf-pro-stubs/master/acf-pro-stubs.php
```

## Deployment

FTP-Deploy github action is setup and deploys `src/` to the live site. It runs
manually (not on push): GitHub → Actions → "🚀 Deploy website" → Run workflow,
picking the branch to deploy.

## Running it locally

Requires docker.

```bash
docker compose up -d          # → http://localhost:8080
docker compose down           # stop, keep data
docker compose down -v        # stop and wipe the database
```

WordPress core lives in a named volume — plugins and uploads survive restarts.
Only `src/` is bind-mounted, so edits on the host show up on reload.

First run needs WordPress installed:

```bash
docker compose --profile cli run --rm wpcli wp core install \
  --url=http://localhost:8080 --title="Rise & Flour" \
  --admin_user=admin --admin_password=admin \
  --admin_email=hello@riseandflour.co.uk --skip-email

docker compose --profile cli run --rm wpcli wp theme activate rise-and-flour
docker compose --profile cli run --rm wpcli wp rewrite structure '/%postname%/'
```

Admin at `/wp-admin` — `admin` / `admin`. The `wpcli` service is opt-in via the
`cli` profile, so it never starts with `up`. Pass the full command including `wp`.

**Verified working:** homepage, inner pages, and 404 all render with the correct
header/footer variants, all assets return 200, and the PHP debug log is clean.

## Static preview

`preview/index.html` and `preview/blocks.html` open directly in a browser with
no WordPress and no build step — they load `../src/css/main.css`. Useful for
checking the design quickly. Their markup mirrors what the theme outputs.

`preview/blocks.html` is the Building Blocks specimen page: all three content
blocks, annotated. It's a design reference, not part of the theme.

## Build

`src/css/main.css` is compiled from `src/scss/` with Dart Sass and committed.
Node isn't installed on this machine, so it was built via Docker:

```bash
docker run --rm -v "$PWD/src:/app" -w /app node:20-alpine \
  npx -y sass scss/main.scss css/main.css --style=expanded --no-source-map
```

Once Node is installed locally, `cd src && npm install && npm run watch` is the
normal loop. Edit the SCSS, never the CSS.

## Theme structure

```
src/
  style.css                  theme header only — no styles live here
  functions.php              setup, enqueues, media helper, nav
  header.php / footer.php    both variants, switched on is_front_page()
  page.php / index.php / 404.php
  blocks/                    one folder per ACF block (block.json + render.php)
  inc/                       blocks.php auto-registration, acf-fields.php
  template-parts/blocks/     the block markup — one source, shared
  scss/                      abstracts · base · layout · blocks · components
  css/main.css               compiled
  js/main.js                 nav panel, reveal-on-scroll header, carousel
  assets/img · assets/fonts  real brand assets
```

## The two navs

| | `.site-header--overlay` | `.site-header--stacked` |
|---|---|---|
| Used on | Front page only | Every other page |
| Position | Absolute — overlays the hero | Static, top of page |
| Background | Transparent | Cream |
| Link colour | Cream | Ink |
| Wordmark | None — hero stamp is the logo | Centred above the nav |
| Below | — | Hairline rule |

Both collapse to the same burger + full-screen ink panel at ≤640px. The nav is a
`<ul>` in both the theme and the preview, matching `wp_nav_menu()` output. Assign
a menu to the **Primary** location; with none assigned it falls back to listing
top-level pages.

## Responsive

- **Desktop** — as drawn: 1100px content column, 48px gutter
- **Tablet ≤1024px** — 32px gutter, tighter image+text gap, footer 3-up
- **Mobile ≤640px** — 20px gutter, image+text stacks (image first), burger nav, single-column footer

The hero uses `100svh` on mobile so browser chrome doesn't crop it.

One deliberate departure from the design: the header hides as you scroll down,
but ~5px of upward scroll slides it back in as a fixed bar
(`.is-floating.is-pinned`), and scrolling down again hides it. At the very top
of the page it rejoins the flow — fixed and natural positions coincide there,
so the floating bar merges seamlessly into the in-page header. On the front
page the floating bar takes an ink backing so the cream links stay legible off
the hero.

## Placeholders

Every photograph is a grey placeholder until real media exists. Blocks call
`rise_and_flour_media()` rather than echoing `<img>`, and it accepts an attachment
ID, a URL, or an **ACF image array** — so wiring ACF in is a one-line change per
block. With nothing passed it emits `<div class="ph">`, sized identically to how
the real image will sit.

Logos and the stamp are real brand assets in `src/assets/img/`. Only photography
is placeholdered.

Real images can be pulled from the live site — `https://riseandflour.co.uk/wp-json/wp/v2/media`
is readable anonymously, no auth or plugin needed.

## ACF blocks

Every page — homepage included — is built by dragging blocks in the editor.
There is deliberately **no `front-page.php`**: the front page is an ordinary Page
called *Home*, so it's editable under Pages like anything else. `header.php` still
picks the overlay nav via `is_front_page()`.

Blocks live under **Rise & Flour** in the block inserter:

| Block | Fields |
|---|---|
| Hero | `image`, `show_logo`, `logo`, `caption` |
| Text | `eyebrow`, `heading`, `body`, `variant` (default/intro), `align`, `tag` (h1–h3), `spacing` |
| Image + Text | `image`, `media_position` (left/right), `eyebrow`, `heading`, `body`, `link_text`, `link_url`, `spacing` |
| Image | `image`, `caption`, `width` (contained/full), `spacing` |
| Carousel | `heading`, `slides` (repeater: image + caption), `size` (large/small), `pager` (dash/dot/diamond), `spacing` |
| Menu | `eyebrow`, `heading`, `tag` (h1/h2), `intro`, `sections` (repeater: title, note, `items` repeater: name, price, description, vegetarian, extras, note), `hours_heading`, `hours` (falls back to Site Details), `notes`, `file` (PDF), `spacing` |
| Location | `eyebrow`, `heading`, `tag` (h1/h2), `intro`, `address`, `phone`, `email` (all fall back to Site Details), `show_hours` (from Site Details), `note`, `directions_url`, `map` (Google Maps embed URL or iframe), `map_position` (left/right), `spacing` |
| Events | `eyebrow`, `heading`, `tag` (h1/h2), `intro`, `show` (both/upcoming/past), `limit`, `past_heading`, `empty_text`, `spacing` — lists the **Events** post type |

### Events

Events are a post type (**Events** in the admin menu, `rf_event`), so each one
has its own page at `/events/<slug>/` rendered by `single-rf_event.php`. Each
event has a title, write-up, featured image and excerpt plus ACF fields in
tabs: **When & where** (`start` required, `end`, `location`, `price`,
`capacity`), **Titles** (`eyebrow`, `subtitle`), **On the night**
(`panel_label`, `courses` repeater, `panel_note` — the ink panel, shown only
when it has lines) and **Booking** (`booking_url`, `booking_text`,
`booking_note`). The single page follows the design export's "Supper Club"
layout: centred head with a bordered date strip, image, write-up, panel,
terracotta reserve button.

The Events block lists them — upcoming (soonest first) then past (most recent
first). An event is "past" once it has ended, or with no end time once its day
is over. The post type has no archive of its own: the page holding the block
is the list, and the single template's "All events" link finds it. Helpers in
`inc/events.php`: `rise_and_flour_event()` (dates formatted for print),
`rise_and_flour_events( 'upcoming'|'past', $limit )`.

Events are included in the content snapshot/restore.

`show_logo` toggles `.hero--no-logo`; `media_position` toggles
`.block-image-text--media-right`; `width` toggles `.block-image--full`; the
carousel's `size` toggles `.carousel--small` (Large fills most of the screen
with the next image running off the page edge; Small is the tighter strip with
a fixed peek of the next image) and its `pager` toggles
`.carousel--pager-dot|diamond` (dashes need no class); `spacing`
maps to `.section--tight|default|loose|xl`, so the same block sits tight or loose
per page rather than having margin baked in.

Every block also supports the editor's **anchor** and **additional CSS class**
controls.

### How it's wired

```
blocks/<name>/block.json   registration (native register_block_type)
blocks/<name>/render.php   reads get_field(), delegates to the template part
template-parts/blocks/     the actual markup — one source, shared
inc/blocks.php             auto-registers every blocks/*/block.json
inc/acf-fields.php         field groups via acf_add_local_field_group()
```

Adding a block means dropping a new folder into `blocks/` and a field group into
`inc/acf-fields.php` — there's no list to keep in sync.

Field groups are **defined in code**, so they're version-controlled and appear on
a fresh install with no import step. The trade-off: they can't be edited in the
ACF admin UI — change `inc/acf-fields.php` instead.

The editor is restricted to these five blocks plus a few core escape hatches
(paragraph, heading, list, image, spacer, html) — see
`rise_and_flour_allowed_blocks()`.

Leave an image field empty and the block renders the grey placeholder, so pages
stay reviewable before photography exists.

### Content snapshot & restore

`tools/content-snapshot.json` (committed) holds every page's block markup, the
front-page setting, and the Site Details options. `tools/seed.php` restores it
after `docker compose down -v` — re-runnable, updates by slug:

```bash
docker compose --profile cli run --rm wpcli wp eval-file /tools/seed.php
```

**Refresh the snapshot whenever content changes**, or the restore will be stale:

```bash
docker compose --profile cli run --rm -T wpcli wp eval-file /tools/snapshot.php > tools/content-snapshot.json
```

Attachment files live in the wiped volume, so restored image blocks fall back
to their grey placeholders — photography is re-added in the editor. Everything
in `tools/` stays outside the theme so it never deploys.

### Gotchas worth knowing

- **Classic Editor was deactivated.** It disables Gutenberg entirely, which makes
  drag-and-drop blocks impossible. Don't re-enable it.
- `wp_insert_post()` unslashes its input, so block markup must be passed through
  `wp_slash()` — otherwise `é` loses its backslash and `café` renders as
  `cafu00e9`. `tools/seed.php` handles this.

### Menu import (PDF → Menu block)

**Site Details → Menu import** updates a page's Menu block from the print
menu PDF. Claude reads the PDF and returns the dishes in the block's own
structure; the page then shows what would be added, removed or changed
against the menu on the site, and nothing is written until **Apply**. The
PDF can also be attached as the block's "Download the menu" link.

It needs an Anthropic API key — define `RF_ANTHROPIC_API_KEY` in
`wp-config.php` (preferred) or paste it into the settings box on the page.
The model defaults to `claude-sonnet-5`. Code: `inc/menu-import.php`; the
PDF is never parsed in PHP — the designed two-column layout makes local text
extraction unreliable.

Headers and footers are **not** blocks — they're `header.php` / `footer.php`.
The footer's address, hours, contact and baseline lines are editable under
**Site Details** in wp-admin (an ACF Pro options page, registered in
`inc/site-options.php`); unset fields fall back to the design's original
values.

## Removed from the underscores scaffold

The café site has no blog, comments, sidebar, or search, so these went:
`inc/` (customizer, custom-header, jetpack, template-tags, template-functions),
`template-parts/content*.php`, `archive.php`, `single.php`, `search.php`,
`sidebar.php`, `comments.php`, `style-rtl.css`, `languages/`, `js/navigation.js`,
`js/customizer.js`, and the lint/composer configs.

`style.css` was reduced to the theme header — the real stylesheet is `css/main.css`.

Still outstanding: `screenshot.png` is the stock underscores image and wants
replacing with a real screenshot.

## Brand rules that constrain the design

From the design system and the export's project notes:

- Rise & Flour is a **café**, not a bakery. Never call it a bakery in new copy.
- No ecommerce anywhere — no shop, basket, or checkout.
- Core Magic is for the **logo and product/dish names only** — never headings or body.
- Headings/nav/labels: Montserrat, always caps, tracked (`.09em` headings, `.22em` labels).
- Body: EB Garamond. Italic for quotes and highlights.
- The ampersand is a brand character — always terracotta (`.amp`).
- Flat backgrounds, no gradients. Corners near-square (3–6px) or fully round.
- Motion is modest: 150–200ms ease-out.