/* @ds-bundle: {"format":4,"namespace":"RiseFlourDesignSystem_1dd5b5","components":[{"name":"Button","sourcePath":"components/actions/Button.jsx"},{"name":"IconButton","sourcePath":"components/actions/IconButton.jsx"},{"name":"Badge","sourcePath":"components/display/Badge.jsx"},{"name":"Card","sourcePath":"components/display/Card.jsx"},{"name":"Divider","sourcePath":"components/display/Divider.jsx"},{"name":"Tag","sourcePath":"components/display/Tag.jsx"},{"name":"Checkbox","sourcePath":"components/forms/Checkbox.jsx"},{"name":"Field","sourcePath":"components/forms/Input.jsx"},{"name":"Input","sourcePath":"components/forms/Input.jsx"},{"name":"Textarea","sourcePath":"components/forms/Input.jsx"},{"name":"Radio","sourcePath":"components/forms/Radio.jsx"},{"name":"RadioGroup","sourcePath":"components/forms/Radio.jsx"},{"name":"Select","sourcePath":"components/forms/Select.jsx"},{"name":"Switch","sourcePath":"components/forms/Switch.jsx"},{"name":"Tabs","sourcePath":"components/navigation/Tabs.jsx"},{"name":"Dialog","sourcePath":"components/overlay/Dialog.jsx"},{"name":"Toast","sourcePath":"components/overlay/Toast.jsx"}],"sourceHashes":{"components/actions/Button.jsx":"d575efc2696b","components/actions/IconButton.jsx":"04f25264efd1","components/display/Badge.jsx":"c58dbb154500","components/display/Card.jsx":"c6a05a4735fe","components/display/Divider.jsx":"21b44597a39f","components/display/Tag.jsx":"d7399935b7b3","components/forms/Checkbox.jsx":"8352224841b2","components/forms/Input.jsx":"d02eb873b5a2","components/forms/Radio.jsx":"b8a7ecf9027d","components/forms/Select.jsx":"c42a65aa6cd1","components/forms/Switch.jsx":"dbca3dc66928","components/navigation/Tabs.jsx":"054aa352f18e","components/overlay/Dialog.jsx":"1c220352e2fd","components/overlay/Toast.jsx":"f01602765137","ui_kits/website/Header.jsx":"47d3e79d485e","ui_kits/website/Home.jsx":"b7bb7d7e6cc3","ui_kits/website/Shop.jsx":"743ab7bdfe5d","ui_kits/website/Visit.jsx":"1752d9efb0b1"},"inlinedExternals":[],"unexposedExports":[]} */

(() => {

const __ds_ns = (window.RiseFlourDesignSystem_1dd5b5 = window.RiseFlourDesignSystem_1dd5b5 || {});

const __ds_scope = {};

(__ds_ns.__errors = __ds_ns.__errors || []);

// components/actions/Button.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Button({
  variant = 'primary',
  size = 'md',
  disabled,
  children,
  style,
  ...rest
}) {
  const pad = size === 'sm' ? '8px 16px' : size === 'lg' ? '14px 28px' : '11px 22px';
  const fs = size === 'sm' ? 12 : 13;
  const base = {
    fontFamily: 'var(--font-sans)',
    fontSize: fs,
    fontWeight: 500,
    letterSpacing: 'var(--tracking-caps)',
    textTransform: 'uppercase',
    padding: pad,
    borderRadius: 'var(--radius-sm)',
    cursor: disabled ? 'not-allowed' : 'pointer',
    opacity: disabled ? 0.45 : 1,
    transition: 'background .15s ease-out, color .15s ease-out, border-color .15s ease-out',
    display: 'inline-flex',
    alignItems: 'center',
    gap: 8
  };
  const variants = {
    primary: {
      background: 'var(--action-primary)',
      color: 'var(--text-on-accent)',
      border: '1.5px solid var(--action-primary)'
    },
    secondary: {
      background: 'transparent',
      color: 'var(--rf-ink)',
      border: '1.5px solid var(--rf-ink)'
    },
    ghost: {
      background: 'transparent',
      color: 'var(--rf-terracotta-deep)',
      border: '1.5px solid transparent'
    },
    inverse: {
      background: 'transparent',
      color: 'var(--rf-cream)',
      border: '1.5px solid var(--rf-cream)'
    }
  };
  const [hover, setHover] = React.useState(false);
  const hovers = {
    primary: {
      background: 'var(--action-primary-hover)',
      borderColor: 'var(--action-primary-hover)'
    },
    secondary: {
      background: 'var(--rf-ink)',
      color: 'var(--rf-cream)'
    },
    ghost: {
      color: 'var(--rf-terracotta)'
    },
    inverse: {
      background: 'var(--rf-cream)',
      color: 'var(--rf-ink)'
    }
  };
  return /*#__PURE__*/React.createElement("button", _extends({}, rest, {
    disabled: disabled,
    onMouseEnter: () => setHover(true),
    onMouseLeave: () => setHover(false),
    style: {
      ...base,
      ...variants[variant],
      ...(hover && !disabled ? hovers[variant] : null),
      ...style
    }
  }), children);
}
Object.assign(__ds_scope, { Button });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/actions/Button.jsx", error: String((e && e.message) || e) }); }

// components/actions/IconButton.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function IconButton({
  label,
  children,
  style,
  ...rest
}) {
  const [hover, setHover] = React.useState(false);
  return /*#__PURE__*/React.createElement("button", _extends({
    "aria-label": label,
    title: label
  }, rest, {
    onMouseEnter: () => setHover(true),
    onMouseLeave: () => setHover(false),
    style: {
      width: 40,
      height: 40,
      borderRadius: '50%',
      border: '1.5px solid ' + (hover ? 'var(--rf-terracotta)' : 'var(--rf-ink)'),
      background: hover ? 'var(--rf-terracotta-wash)' : 'transparent',
      color: hover ? 'var(--rf-terracotta-deep)' : 'var(--rf-ink)',
      cursor: 'pointer',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      transition: 'all .15s ease-out',
      ...style
    }
  }), children);
}
Object.assign(__ds_scope, { IconButton });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/actions/IconButton.jsx", error: String((e && e.message) || e) }); }

// components/display/Badge.jsx
try { (() => {
function Badge({
  tone = 'accent',
  children
}) {
  const tones = {
    accent: {
      background: 'var(--rf-terracotta)',
      color: 'var(--rf-cream)',
      border: '1.5px solid var(--rf-terracotta)'
    },
    ink: {
      background: 'var(--rf-ink)',
      color: 'var(--rf-cream)',
      border: '1.5px solid var(--rf-ink)'
    },
    outline: {
      background: 'transparent',
      color: 'var(--rf-ink)',
      border: '1.5px solid var(--rf-ink)'
    }
  };
  return /*#__PURE__*/React.createElement("span", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      padding: '4px 12px',
      borderRadius: 'var(--radius-pill)',
      fontFamily: 'var(--font-sans)',
      fontSize: 11,
      fontWeight: 500,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      ...tones[tone]
    }
  }, children);
}
Object.assign(__ds_scope, { Badge });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/display/Badge.jsx", error: String((e && e.message) || e) }); }

// components/display/Card.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Card({
  framed,
  interactive,
  image,
  imageAlt,
  eyebrow,
  title,
  children,
  footer,
  style,
  ...rest
}) {
  const [hover, setHover] = React.useState(false);
  return /*#__PURE__*/React.createElement("div", _extends({}, rest, {
    onMouseEnter: () => setHover(true),
    onMouseLeave: () => setHover(false),
    style: {
      background: 'var(--surface-card)',
      border: framed ? '1.5px solid var(--rf-terracotta)' : 'var(--rule-thin)',
      borderRadius: 'var(--radius-md)',
      overflow: 'hidden',
      boxShadow: interactive && hover ? 'var(--shadow-lift)' : 'var(--shadow-card)',
      transition: 'box-shadow .2s ease-out',
      cursor: interactive ? 'pointer' : 'default',
      fontFamily: 'var(--font-sans)',
      ...style
    }
  }), framed ? /*#__PURE__*/React.createElement("div", {
    style: {
      margin: 4,
      border: '1px solid var(--rf-terracotta)',
      borderRadius: 3
    }
  }, /*#__PURE__*/React.createElement(CardBody, {
    image: image,
    imageAlt: imageAlt,
    eyebrow: eyebrow,
    title: title,
    footer: footer
  }, children)) : /*#__PURE__*/React.createElement(CardBody, {
    image: image,
    imageAlt: imageAlt,
    eyebrow: eyebrow,
    title: title,
    footer: footer
  }, children));
}
function CardBody({
  image,
  imageAlt,
  eyebrow,
  title,
  children,
  footer
}) {
  return /*#__PURE__*/React.createElement(React.Fragment, null, image && /*#__PURE__*/React.createElement("img", {
    src: image,
    alt: imageAlt || '',
    style: {
      width: '100%',
      display: 'block',
      aspectRatio: '3/2',
      objectFit: 'cover'
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: '18px 20px'
    }
  }, eyebrow && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-accent)',
      marginBottom: 8
    }
  }, "\xB7 ", eyebrow, " \xB7"), title && /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: 'var(--font-display)',
      fontSize: 22,
      color: 'var(--text-heading)',
      lineHeight: 1.15,
      marginBottom: children ? 8 : 0
    }
  }, title), children && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 14,
      color: 'var(--text-body)',
      lineHeight: 1.6
    }
  }, children), footer && /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 14,
      paddingTop: 12,
      borderTop: 'var(--rule-thin)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'space-between',
      gap: 12
    }
  }, footer)));
}
Object.assign(__ds_scope, { Card });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/display/Card.jsx", error: String((e && e.message) || e) }); }

// components/display/Divider.jsx
try { (() => {
function Divider({
  label,
  style
}) {
  if (!label) return /*#__PURE__*/React.createElement("hr", {
    style: {
      border: 'none',
      borderTop: 'var(--rule-thin)',
      margin: '24px 0',
      ...style
    }
  });
  return /*#__PURE__*/React.createElement("div", {
    role: "separator",
    style: {
      display: 'flex',
      alignItems: 'center',
      gap: 16,
      margin: '24px 0',
      ...style
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      flex: 1,
      borderTop: 'var(--rule-thin)'
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: 'var(--font-sans)',
      fontSize: 11,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-muted)'
    }
  }, "\xB7 ", label, " \xB7"), /*#__PURE__*/React.createElement("span", {
    style: {
      flex: 1,
      borderTop: 'var(--rule-thin)'
    }
  }));
}
Object.assign(__ds_scope, { Divider });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/display/Divider.jsx", error: String((e && e.message) || e) }); }

// components/display/Tag.jsx
try { (() => {
function Tag({
  onRemove,
  children
}) {
  return /*#__PURE__*/React.createElement("span", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: 8,
      padding: '5px 12px',
      borderRadius: 'var(--radius-pill)',
      background: 'var(--rf-terracotta-wash)',
      border: '1px solid var(--rf-terracotta-tint)',
      color: 'var(--rf-terracotta-deep)',
      fontFamily: 'var(--font-sans)',
      fontSize: 13
    }
  }, children, onRemove && /*#__PURE__*/React.createElement("button", {
    onClick: onRemove,
    "aria-label": "Remove",
    style: {
      border: 'none',
      background: 'none',
      color: 'inherit',
      cursor: 'pointer',
      padding: 0,
      fontSize: 14,
      lineHeight: 1
    }
  }, "\xD7"));
}
Object.assign(__ds_scope, { Tag });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/display/Tag.jsx", error: String((e && e.message) || e) }); }

// components/forms/Checkbox.jsx
try { (() => {
function Checkbox({
  label,
  checked,
  defaultChecked,
  onChange,
  disabled
}) {
  const [on, setOn] = React.useState(!!defaultChecked);
  const isOn = checked !== undefined ? checked : on;
  const toggle = e => {
    if (checked === undefined) setOn(e.target.checked);
    onChange && onChange(e);
  };
  return /*#__PURE__*/React.createElement("label", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: 10,
      fontFamily: 'var(--font-sans)',
      fontSize: 15,
      color: 'var(--text-body)',
      cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.45 : 1
    }
  }, /*#__PURE__*/React.createElement("input", {
    type: "checkbox",
    checked: isOn,
    onChange: toggle,
    disabled: disabled,
    style: {
      position: 'absolute',
      opacity: 0,
      width: 0
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      width: 20,
      height: 20,
      flex: 'none',
      borderRadius: 'var(--radius-sm)',
      border: '1.5px solid ' + (isOn ? 'var(--rf-terracotta)' : 'var(--rf-ink)'),
      background: isOn ? 'var(--rf-terracotta)' : 'transparent',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      transition: 'all .15s ease-out'
    }
  }, isOn && /*#__PURE__*/React.createElement("svg", {
    width: "12",
    height: "12",
    viewBox: "0 0 12 12"
  }, /*#__PURE__*/React.createElement("path", {
    d: "M2 6.5 5 9.5 10 3",
    fill: "none",
    stroke: "var(--rf-cream)",
    strokeWidth: "2",
    strokeLinecap: "round",
    strokeLinejoin: "round"
  }))), label);
}
Object.assign(__ds_scope, { Checkbox });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Checkbox.jsx", error: String((e && e.message) || e) }); }

// components/forms/Input.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Field({
  label,
  hint,
  children
}) {
  return /*#__PURE__*/React.createElement("label", {
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: 6,
      fontFamily: 'var(--font-sans)'
    }
  }, label && /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-heading)'
    }
  }, label), children, hint && /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 12,
      color: 'var(--text-muted)'
    }
  }, hint));
}
function Input({
  label,
  hint,
  invalid,
  style,
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const el = /*#__PURE__*/React.createElement("input", _extends({}, rest, {
    onFocus: e => {
      setFocus(true);
      rest.onFocus && rest.onFocus(e);
    },
    onBlur: e => {
      setFocus(false);
      rest.onBlur && rest.onBlur(e);
    },
    style: {
      fontFamily: 'var(--font-sans)',
      fontSize: 15,
      color: 'var(--text-heading)',
      padding: '10px 14px',
      background: 'var(--surface-raised)',
      border: '1.5px solid ' + (invalid ? 'var(--rf-terracotta-deep)' : focus ? 'var(--rf-terracotta)' : 'var(--border-default)'),
      borderRadius: 'var(--radius-sm)',
      outline: 'none',
      boxShadow: focus ? 'var(--focus-ring)' : 'none',
      transition: 'border-color .15s, box-shadow .15s',
      ...style
    }
  }));
  return label || hint ? /*#__PURE__*/React.createElement(Field, {
    label: label,
    hint: hint
  }, el) : el;
}
function Textarea({
  label,
  hint,
  style,
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const el = /*#__PURE__*/React.createElement("textarea", _extends({
    rows: 4
  }, rest, {
    onFocus: () => setFocus(true),
    onBlur: () => setFocus(false),
    style: {
      fontFamily: 'var(--font-sans)',
      fontSize: 15,
      color: 'var(--text-heading)',
      padding: '10px 14px',
      background: 'var(--surface-raised)',
      border: '1.5px solid ' + (focus ? 'var(--rf-terracotta)' : 'var(--border-default)'),
      borderRadius: 'var(--radius-sm)',
      outline: 'none',
      boxShadow: focus ? 'var(--focus-ring)' : 'none',
      resize: 'vertical',
      ...style
    }
  }));
  return label || hint ? /*#__PURE__*/React.createElement(Field, {
    label: label,
    hint: hint
  }, el) : el;
}
Object.assign(__ds_scope, { Field, Input, Textarea });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Input.jsx", error: String((e && e.message) || e) }); }

// components/forms/Radio.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Radio({
  label,
  checked,
  disabled,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("label", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: 10,
      fontFamily: 'var(--font-sans)',
      fontSize: 15,
      color: 'var(--text-body)',
      cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.45 : 1
    }
  }, /*#__PURE__*/React.createElement("input", _extends({
    type: "radio",
    checked: checked,
    disabled: disabled
  }, rest, {
    style: {
      position: 'absolute',
      opacity: 0,
      width: 0
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      width: 20,
      height: 20,
      flex: 'none',
      borderRadius: '50%',
      border: '1.5px solid ' + (checked ? 'var(--rf-terracotta)' : 'var(--rf-ink)'),
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      transition: 'all .15s ease-out'
    }
  }, checked && /*#__PURE__*/React.createElement("span", {
    style: {
      width: 10,
      height: 10,
      borderRadius: '50%',
      background: 'var(--rf-terracotta)'
    }
  })), label);
}
function RadioGroup({
  name,
  options = [],
  value,
  onChange,
  direction = 'column'
}) {
  return /*#__PURE__*/React.createElement("div", {
    role: "radiogroup",
    style: {
      display: 'flex',
      flexDirection: direction,
      gap: direction === 'row' ? 20 : 10
    }
  }, options.map(o => {
    const v = o.value ?? o,
      l = o.label ?? o;
    return /*#__PURE__*/React.createElement(Radio, {
      key: v,
      name: name,
      label: l,
      checked: value === v,
      onChange: () => onChange && onChange(v)
    });
  }));
}
Object.assign(__ds_scope, { Radio, RadioGroup });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Radio.jsx", error: String((e && e.message) || e) }); }

// components/forms/Select.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Select({
  label,
  hint,
  options = [],
  style,
  ...rest
}) {
  const [focus, setFocus] = React.useState(false);
  const el = /*#__PURE__*/React.createElement("div", {
    style: {
      position: 'relative',
      display: 'flex'
    }
  }, /*#__PURE__*/React.createElement("select", _extends({}, rest, {
    onFocus: () => setFocus(true),
    onBlur: () => setFocus(false),
    style: {
      appearance: 'none',
      WebkitAppearance: 'none',
      width: '100%',
      fontFamily: 'var(--font-sans)',
      fontSize: 15,
      color: 'var(--text-heading)',
      padding: '10px 38px 10px 14px',
      background: 'var(--surface-raised)',
      border: '1.5px solid ' + (focus ? 'var(--rf-terracotta)' : 'var(--border-default)'),
      borderRadius: 'var(--radius-sm)',
      outline: 'none',
      boxShadow: focus ? 'var(--focus-ring)' : 'none',
      cursor: 'pointer',
      ...style
    }
  }), options.map(o => /*#__PURE__*/React.createElement("option", {
    key: o.value ?? o,
    value: o.value ?? o
  }, o.label ?? o))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: 'absolute',
      right: 14,
      top: '50%',
      transform: 'translateY(-58%) rotate(45deg)',
      width: 7,
      height: 7,
      borderRight: '1.5px solid var(--rf-ink)',
      borderBottom: '1.5px solid var(--rf-ink)',
      pointerEvents: 'none'
    }
  }));
  return label || hint ? /*#__PURE__*/React.createElement(__ds_scope.Field, {
    label: label,
    hint: hint
  }, el) : el;
}
Object.assign(__ds_scope, { Select });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Select.jsx", error: String((e && e.message) || e) }); }

// components/forms/Switch.jsx
try { (() => {
function Switch({
  label,
  checked,
  defaultChecked,
  onChange,
  disabled
}) {
  const [on, setOn] = React.useState(!!defaultChecked);
  const isOn = checked !== undefined ? checked : on;
  const toggle = () => {
    if (disabled) return;
    if (checked === undefined) setOn(!isOn);
    onChange && onChange(!isOn);
  };
  return /*#__PURE__*/React.createElement("label", {
    style: {
      display: 'inline-flex',
      alignItems: 'center',
      gap: 10,
      fontFamily: 'var(--font-sans)',
      fontSize: 15,
      color: 'var(--text-body)',
      cursor: disabled ? 'not-allowed' : 'pointer',
      opacity: disabled ? 0.45 : 1
    }
  }, /*#__PURE__*/React.createElement("button", {
    role: "switch",
    "aria-checked": isOn,
    onClick: toggle,
    style: {
      width: 44,
      height: 24,
      flex: 'none',
      borderRadius: 'var(--radius-pill)',
      border: '1.5px solid ' + (isOn ? 'var(--rf-terracotta)' : 'var(--rf-ink)'),
      background: isOn ? 'var(--rf-terracotta)' : 'transparent',
      position: 'relative',
      cursor: 'inherit',
      transition: 'all .15s ease-out',
      padding: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: 'absolute',
      top: 2,
      left: isOn ? 22 : 2,
      width: 17,
      height: 17,
      borderRadius: '50%',
      background: isOn ? 'var(--rf-cream)' : 'var(--rf-ink)',
      transition: 'left .15s ease-out, background .15s ease-out'
    }
  })), label);
}
Object.assign(__ds_scope, { Switch });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Switch.jsx", error: String((e && e.message) || e) }); }

// components/navigation/Tabs.jsx
try { (() => {
function Tabs({
  items = [],
  value,
  defaultValue,
  onChange
}) {
  const [cur, setCur] = React.useState(defaultValue ?? (items[0] && (items[0].value ?? items[0])));
  const active = value !== undefined ? value : cur;
  return /*#__PURE__*/React.createElement("div", {
    role: "tablist",
    style: {
      display: 'flex',
      gap: 28,
      borderBottom: 'var(--rule-thin)',
      fontFamily: 'var(--font-sans)'
    }
  }, items.map(it => {
    const v = it.value ?? it,
      l = it.label ?? it;
    const on = v === active;
    return /*#__PURE__*/React.createElement("button", {
      key: v,
      role: "tab",
      "aria-selected": on,
      onClick: () => {
        if (value === undefined) setCur(v);
        onChange && onChange(v);
      },
      style: {
        background: 'none',
        border: 'none',
        padding: '10px 2px 12px',
        marginBottom: -1,
        cursor: 'pointer',
        fontSize: 12,
        letterSpacing: 'var(--tracking-caps)',
        textTransform: 'uppercase',
        color: on ? 'var(--rf-terracotta-deep)' : 'var(--text-muted)',
        borderBottom: '2px solid ' + (on ? 'var(--rf-terracotta)' : 'transparent'),
        transition: 'color .15s'
      }
    }, l);
  }));
}
Object.assign(__ds_scope, { Tabs });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/navigation/Tabs.jsx", error: String((e && e.message) || e) }); }

// components/overlay/Dialog.jsx
try { (() => {
function Dialog({
  open,
  onClose,
  eyebrow,
  title,
  children,
  actions
}) {
  if (!open) return null;
  return /*#__PURE__*/React.createElement("div", {
    onClick: onClose,
    style: {
      position: 'fixed',
      inset: 0,
      background: 'rgba(32,60,67,.55)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: 100,
      padding: 24
    }
  }, /*#__PURE__*/React.createElement("div", {
    role: "dialog",
    "aria-modal": "true",
    onClick: e => e.stopPropagation(),
    style: {
      background: 'var(--rf-paper)',
      borderRadius: 'var(--radius-md)',
      border: '1.5px solid var(--rf-terracotta)',
      boxShadow: 'var(--shadow-modal)',
      maxWidth: 460,
      width: '100%',
      padding: 4,
      fontFamily: 'var(--font-sans)'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      border: '1px solid var(--rf-terracotta)',
      borderRadius: 3,
      padding: '28px 28px 24px',
      position: 'relative'
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onClose,
    "aria-label": "Close",
    style: {
      position: 'absolute',
      top: 12,
      right: 14,
      border: 'none',
      background: 'none',
      fontSize: 20,
      color: 'var(--text-muted)',
      cursor: 'pointer'
    }
  }, "\xD7"), eyebrow && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-accent)',
      textAlign: 'center',
      marginBottom: 8
    }
  }, "\xB7 ", eyebrow, " \xB7"), title && /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: 'var(--font-display)',
      fontSize: 26,
      color: 'var(--text-heading)',
      textAlign: 'center',
      marginBottom: 12
    }
  }, title), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 15,
      color: 'var(--text-body)',
      textAlign: 'center'
    }
  }, children), actions && /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      justifyContent: 'center',
      gap: 12,
      marginTop: 22
    }
  }, actions))));
}
Object.assign(__ds_scope, { Dialog });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/overlay/Dialog.jsx", error: String((e && e.message) || e) }); }

// components/overlay/Toast.jsx
try { (() => {
function Toast({
  open = true,
  title,
  children,
  onClose
}) {
  if (!open) return null;
  return /*#__PURE__*/React.createElement("div", {
    role: "status",
    style: {
      display: 'inline-flex',
      alignItems: 'flex-start',
      gap: 12,
      background: 'var(--rf-ink)',
      color: 'var(--rf-cream)',
      borderRadius: 'var(--radius-md)',
      padding: '14px 18px',
      boxShadow: 'var(--shadow-lift)',
      fontFamily: 'var(--font-sans)',
      maxWidth: 380
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 8,
      height: 8,
      borderRadius: '50%',
      background: 'var(--rf-terracotta)',
      marginTop: 6,
      flex: 'none'
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      flex: 1
    }
  }, title && /*#__PURE__*/React.createElement("span", {
    style: {
      display: 'block',
      fontSize: 12,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      marginBottom: 3
    }
  }, title), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 14,
      color: 'var(--rf-ink-faint)'
    }
  }, children)), onClose && /*#__PURE__*/React.createElement("button", {
    onClick: onClose,
    "aria-label": "Dismiss",
    style: {
      border: 'none',
      background: 'none',
      color: 'var(--rf-ink-faint)',
      cursor: 'pointer',
      fontSize: 16,
      padding: 0,
      lineHeight: 1
    }
  }, "\xD7"));
}
Object.assign(__ds_scope, { Toast });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/overlay/Toast.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Header.jsx
try { (() => {
const {
  Button
} = window.RiseFlourDesignSystem_1dd5b5;
function Header({
  nav,
  setNav,
  basketCount,
  openBasket
}) {
  const items = ['Home', 'Shop', 'Visit'];
  return /*#__PURE__*/React.createElement("header", {
    style: {
      background: 'var(--rf-ink)',
      padding: '0 40px',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'space-between',
      height: 72
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "../../assets/monogram-rf.png",
    alt: "Rise & Flour",
    style: {
      height: 30,
      cursor: 'pointer',
      filter: 'brightness(0) saturate(100%) invert(93%) sepia(10%) saturate(500%) hue-rotate(10deg)'
    },
    onClick: () => setNav('Home')
  }), /*#__PURE__*/React.createElement("nav", {
    style: {
      display: 'flex',
      gap: 36
    }
  }, items.map(it => /*#__PURE__*/React.createElement("a", {
    key: it,
    href: "#",
    onClick: e => {
      e.preventDefault();
      setNav(it);
    },
    style: {
      fontSize: 12,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      textDecoration: 'none',
      color: nav === it ? 'var(--rf-terracotta)' : 'var(--rf-cream)',
      borderBottom: '2px solid ' + (nav === it ? 'var(--rf-terracotta)' : 'transparent'),
      paddingBottom: 4,
      transition: 'color .15s'
    }
  }, it))), /*#__PURE__*/React.createElement("button", {
    onClick: openBasket,
    style: {
      background: 'none',
      border: '1.5px solid var(--rf-cream)',
      borderRadius: 'var(--radius-pill)',
      color: 'var(--rf-cream)',
      padding: '8px 18px',
      fontFamily: 'var(--font-sans)',
      fontSize: 12,
      letterSpacing: 'var(--tracking-wide)',
      textTransform: 'uppercase',
      cursor: 'pointer'
    }
  }, "Basket \xB7 ", basketCount));
}
function Footer() {
  return /*#__PURE__*/React.createElement("footer", {
    style: {
      background: 'var(--rf-ink)',
      marginTop: 80,
      padding: '48px 40px',
      textAlign: 'center'
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "../../assets/logo-round-white.png",
    alt: "",
    style: {
      width: 88,
      borderRadius: '50%'
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      color: 'var(--rf-cream)',
      fontSize: 12,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      marginTop: 16
    }
  }, "\xB7 Artisan Bakery in the Cotswolds \xB7"), /*#__PURE__*/React.createElement("div", {
    style: {
      color: 'var(--rf-ink-muted)',
      fontSize: 13,
      marginTop: 10
    }
  }, "3 Market Place, Cirencester \xB7 Wed\u2013Sun from 8am"));
}
Object.assign(window, {
  Header,
  Footer
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Header.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Home.jsx
try { (() => {
const {
  Button,
  Card,
  Badge,
  Divider
} = window.RiseFlourDesignSystem_1dd5b5;
function Home({
  addToBasket,
  goShop
}) {
  return /*#__PURE__*/React.createElement("main", null, /*#__PURE__*/React.createElement("section", {
    style: {
      textAlign: 'center',
      padding: '72px 24px 56px'
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "../../assets/logo-round-cream.png",
    alt: "Rise & Flour \u2014 Artisan Bakery in the Cotswolds",
    style: {
      width: 300
    }
  }), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontSize: 'var(--text-h1)',
      margin: '36px 0 12px'
    }
  }, "Baked before dawn. Gone by noon."), /*#__PURE__*/React.createElement("p", {
    style: {
      maxWidth: '48ch',
      margin: '0 auto 28px',
      fontSize: 'var(--text-lead)'
    }
  }, "Slow-fermented sourdough, stone-milled flour, and nothing you couldn't pronounce."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      gap: 14,
      justifyContent: 'center'
    }
  }, /*#__PURE__*/React.createElement(Button, {
    onClick: goShop
  }, "Order for collection"), /*#__PURE__*/React.createElement(Button, {
    variant: "secondary"
  }, "Our story"))), /*#__PURE__*/React.createElement("section", {
    style: {
      maxWidth: 1100,
      margin: '0 auto',
      padding: '0 24px'
    }
  }, /*#__PURE__*/React.createElement(Divider, {
    label: "From the oven"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: 'repeat(3, 1fr)',
      gap: 24,
      marginTop: 32
    }
  }, LOAVES.slice(0, 3).map(l => /*#__PURE__*/React.createElement(Card, {
    key: l.name,
    framed: l.seasonal,
    interactive: true,
    eyebrow: l.eyebrow,
    title: l.name,
    footer: /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("span", {
      style: {
        fontFamily: 'var(--font-display)',
        fontSize: 18,
        color: 'var(--text-heading)'
      }
    }, l.price), /*#__PURE__*/React.createElement(Button, {
      size: "sm",
      onClick: () => addToBasket(l)
    }, "Add"))
  }, l.desc)))), /*#__PURE__*/React.createElement("section", {
    style: {
      maxWidth: 760,
      margin: '72px auto 0',
      padding: '0 24px',
      display: 'flex',
      gap: 36,
      alignItems: 'center'
    }
  }, /*#__PURE__*/React.createElement("img", {
    src: "../../assets/illustration-victorian-lady.jpg",
    alt: "Victorian engraving of a woman in a plumed hat",
    style: {
      width: 200
    }
  }), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-accent)'
    }
  }, "\xB7 Est. Cirencester \xB7"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: 'var(--text-h2)',
      margin: '10px 0 12px'
    }
  }, "A proper bakery, the old way"), /*#__PURE__*/React.createElement("p", {
    style: {
      margin: 0
    }
  }, "We mill on stone, ferment on patience, and bake in a deck oven older than most of our recipes. Everything is made by hand, in small batches, seven days' rest for the starter and none for us."))));
}
window.Home = Home;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Home.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Shop.jsx
try { (() => {
const {
  Button,
  Card,
  Badge,
  Tabs,
  Divider
} = window.RiseFlourDesignSystem_1dd5b5;
function Shop({
  addToBasket
}) {
  const [tab, setTab] = React.useState('Breads');
  const items = LOAVES.filter(l => l.cat === tab);
  return /*#__PURE__*/React.createElement("main", {
    style: {
      maxWidth: 1100,
      margin: '0 auto',
      padding: '56px 24px 0'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: 'center',
      marginBottom: 36
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-accent)'
    }
  }, "\xB7 Order for collection \xB7"), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontSize: 'var(--text-h1)',
      margin: '10px 0 0'
    }
  }, "The Shop")), /*#__PURE__*/React.createElement(Tabs, {
    items: ['Breads', 'Pastries', 'Pantry'],
    value: tab,
    onChange: setTab
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: 'repeat(3, 1fr)',
      gap: 24,
      marginTop: 32
    }
  }, items.map(l => /*#__PURE__*/React.createElement(Card, {
    key: l.name,
    framed: l.seasonal,
    interactive: true,
    eyebrow: l.eyebrow,
    title: l.name,
    footer: /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("span", {
      style: {
        fontFamily: 'var(--font-display)',
        fontSize: 18,
        color: 'var(--text-heading)'
      }
    }, l.price), /*#__PURE__*/React.createElement(Button, {
      size: "sm",
      onClick: () => addToBasket(l)
    }, "Add"))
  }, l.desc))));
}
window.Shop = Shop;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Shop.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/Visit.jsx
try { (() => {
const {
  Button,
  Input,
  Textarea,
  Select,
  Divider
} = window.RiseFlourDesignSystem_1dd5b5;
function Visit() {
  const [sent, setSent] = React.useState(false);
  return /*#__PURE__*/React.createElement("main", {
    style: {
      maxWidth: 900,
      margin: '0 auto',
      padding: '56px 24px 0'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: 'center',
      marginBottom: 40
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-accent)'
    }
  }, "\xB7 Find us \xB7"), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontSize: 'var(--text-h1)',
      margin: '10px 0 0'
    }
  }, "Visit the bakery")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'grid',
      gridTemplateColumns: '1fr 1.2fr',
      gap: 48
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h3", {
    style: {
      fontSize: 'var(--text-h3)',
      marginTop: 0
    }
  }, "Hours"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: 8,
      fontSize: 15
    }
  }, [['Wednesday – Friday', '8am – 2pm'], ['Saturday', '8am – 1pm'], ['Sunday', '9am – 12pm'], ['Monday & Tuesday', 'Resting the starter']].map(([d, h]) => /*#__PURE__*/React.createElement("div", {
    key: d,
    style: {
      display: 'flex',
      justifyContent: 'space-between',
      borderBottom: 'var(--rule-thin)',
      paddingBottom: 8
    }
  }, /*#__PURE__*/React.createElement("span", null, d), /*#__PURE__*/React.createElement("span", {
    style: {
      color: 'var(--text-muted)'
    }
  }, h)))), /*#__PURE__*/React.createElement("h3", {
    style: {
      fontSize: 'var(--text-h3)',
      marginTop: 32
    }
  }, "Address"), /*#__PURE__*/React.createElement("p", {
    style: {
      margin: 0
    }
  }, "3 Market Place", /*#__PURE__*/React.createElement("br", null), "Cirencester, Gloucestershire", /*#__PURE__*/React.createElement("br", null), "GL7 2NX")), /*#__PURE__*/React.createElement("div", {
    style: {
      background: 'var(--surface-card)',
      border: '1.5px solid var(--rf-terracotta)',
      borderRadius: 'var(--radius-md)',
      padding: 4
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      border: '1px solid var(--rf-terracotta)',
      borderRadius: 3,
      padding: 28
    }
  }, sent ? /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: 'center',
      padding: '40px 0'
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: 'var(--font-display)',
      fontSize: 26,
      color: 'var(--text-heading)'
    }
  }, "Thank you"), /*#__PURE__*/React.createElement("p", null, "We read everything, usually with flour on our hands. We'll reply within a day or two.")) : /*#__PURE__*/React.createElement("div", {
    style: {
      display: 'flex',
      flexDirection: 'column',
      gap: 18
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      letterSpacing: 'var(--tracking-caps)',
      textTransform: 'uppercase',
      color: 'var(--text-accent)',
      textAlign: 'center'
    }
  }, "\xB7 Write to us \xB7"), /*#__PURE__*/React.createElement(Input, {
    label: "Name",
    placeholder: "Your name"
  }), /*#__PURE__*/React.createElement(Input, {
    label: "Email",
    placeholder: "you@example.com"
  }), /*#__PURE__*/React.createElement(Select, {
    label: "Subject",
    options: ['Wholesale enquiry', 'Large order', 'Something else']
  }), /*#__PURE__*/React.createElement(Textarea, {
    label: "Message",
    placeholder: "Tell us what you need"
  }), /*#__PURE__*/React.createElement(Button, {
    onClick: () => setSent(true)
  }, "Send"))))));
}
window.Visit = Visit;
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/Visit.jsx", error: String((e && e.message) || e) }); }

__ds_ns.Button = __ds_scope.Button;

__ds_ns.IconButton = __ds_scope.IconButton;

__ds_ns.Badge = __ds_scope.Badge;

__ds_ns.Card = __ds_scope.Card;

__ds_ns.Divider = __ds_scope.Divider;

__ds_ns.Tag = __ds_scope.Tag;

__ds_ns.Checkbox = __ds_scope.Checkbox;

__ds_ns.Field = __ds_scope.Field;

__ds_ns.Input = __ds_scope.Input;

__ds_ns.Textarea = __ds_scope.Textarea;

__ds_ns.Radio = __ds_scope.Radio;

__ds_ns.RadioGroup = __ds_scope.RadioGroup;

__ds_ns.Select = __ds_scope.Select;

__ds_ns.Switch = __ds_scope.Switch;

__ds_ns.Tabs = __ds_scope.Tabs;

__ds_ns.Dialog = __ds_scope.Dialog;

__ds_ns.Toast = __ds_scope.Toast;

})();
