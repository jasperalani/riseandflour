# Rise & Flour Design System

Artisan bakery in the Cotswolds. Victorian-apothecary meets modern craft bakery: engraved illustration, slab-serif wordmark, spaced-out caps, a warm three-colour palette on cream paper.

## Sources
- Uploaded brand assets (see `assets/`): round logo (cream + white bg), rectangular stamp, wordmark, R&F monogram, Victorian engraving illustration (iStock-471289793).
- Brand font file: `S-Core - Core Magic.otf` (slab serif, used for the wordmark).
- Brand colours supplied by owner: `#d5844f` (terracotta), `#203c43` (ink teal).
- No codebase, Figma, or existing product UI was provided — components and the UI kit are original compositions built strictly from these brand foundations.

## Content fundamentals
- Voice: warm, unhurried, craft-proud. Short declarative sentences. British English (flavour, colour).
- The brand speaks as "we", addresses the reader as "you". First-person plural, never corporate third person.
- Labels, nav, buttons and eyebrows are LETTERSPACED CAPS ("ARTISAN BAKERY IN THE COTSWOLDS"). Headings are Title Case or sentence case; body is sentence case.
- The ampersand is a brand character — always terracotta when set in display type (Rise **&** Flour, Bread **&** Pastry).
- Interpuncts (·) bracket short cap labels, echoing the stamp: `· EST. CIRENCESTER ·`.
- No emoji, no exclamation-mark enthusiasm. Confidence through restraint: "Baked before dawn. Gone by noon."
- Example copy: "Slow-fermented sourdough, stone-milled flour, and nothing you couldn't pronounce."

## Visual foundations
- **Colour**: three colours do all the work. Ink teal `#203c43` for text and structure; terracotta `#d5844f` for accents, actions and the ampersand; cream `#f9f5e4` as the page. White reserved for raised surfaces. Tints derived from these; no other hues.
- **Type**: Core Magic (slab serif) for display/headings — bold, tight leading. Jost (geometric sans, Google Fonts) for UI, labels and body — a **substitute** for the unnamed geometric sans in the tagline lockup; see Caveats. Caps labels get `letter-spacing: .22em`.
- **Motif — the stamp**: double rules (thin outer + thin inner line, terracotta) frame important content, as on the logo. Cards and framed sections may use a single 1.5px rule instead of shadow.
- **Illustration**: Victorian copper-engraving style, ink-teal line art on cream. Never redraw; use provided assets. Photography, if any, should be warm, natural light, slightly desaturated.
- **Backgrounds**: flat cream or flat ink. No gradients, no patterns, no blur/transparency effects.
- **Corners**: near-square (3–6px) or fully round (pills, circular stamps). Nothing in between.
- **Shadows**: mostly flat. `--shadow-lift` only for hover on interactive cards; `--shadow-modal` for dialogs.
- **Hover**: darker terracotta for primary actions; underline or terracotta text for links; gentle lift for cards. Press: darken + no transform gimmicks.
- **Motion**: modest. 150–200ms ease-out fades and color transitions only; no bounces or parallax.
- **Layout**: generous whitespace, centered lockups for brand moments, left-aligned for content. Max content width ~1100px.

## Iconography
- No icon set exists in the brand sources. The brand's "icons" are typographic: the terracotta ampersand, interpuncts (·), and the circular stamp.
- For functional UI icons use **Lucide** (CDN), stroke width 1.75, sized 16–20px, coloured `--rf-ink` or `--rf-terracotta`. This is a flagged addition, not a brand source.
- Never hand-draw SVG imitations of the engraving or logo.

## Intentional additions
- Jost webfont (body/UI sans) — no body font was supplied.
- Lucide icons — no icon set was supplied.
- Standard component set (no product source existed to enumerate one).

## Index
- `styles.css` → imports `tokens/` (fonts, colors, typography, shape).
- `assets/` — logos (round cream/white, stamp, wordmark, R&F monogram), Victorian engraving, Core Magic font.
- `guidelines/` — specimen cards shown in the Design System tab.
- `components/` — actions: Button, IconButton · forms: Input, Textarea, Field, Select, Checkbox, Radio, RadioGroup, Switch · display: Card, Badge, Tag, Divider · navigation: Tabs · overlay: Dialog, Toast.
- `ui_kits/website/` — bakery website: interactive homepage recreation-style composition (`index.html` + JSX screens).
- `SKILL.md` — agent-skill entry point.

## Caveats
- Body/UI sans is a Google Fonts substitution (Jost ≈ the geometric sans in the tagline). Supply the real font file to replace it.
- Core Magic OTF ships only one file; weights beyond its native weight are synthesised.
