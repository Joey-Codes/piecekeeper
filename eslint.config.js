import pluginVue from "eslint-plugin-vue";

export default [
    ...pluginVue.configs["flat/recommended"],
    {
        rules: {
            "vue/html-indent": ["error", 4],
            "vue/script-indent": ["error", 4, { baseIndent: 0, switchCase: 1 }],

            // Disable opinionated rules we don't want
            "vue/multi-word-component-names": "off",
            // "vue/singleline-html-element-content-newline": "off",
            // "vue/max-attributes-per-line": "off",
            // "vue/html-self-closing": "off",
            // "vue/attributes-order": "off",
            // "vue/attribute-hyphenation": "off",
            // "vue/v-on-event-shorthands": "off",
            // "vue/first-attribute-linebreak": "off",
            // "vue/html-closing-bracket-newline": "off",
        },
    },
    {
        // Disable core indent rule for .vue files (vue/script-indent handles it)
        files: ["**/*.vue"],
        rules: {
            indent: "off",
        },
    },
];
