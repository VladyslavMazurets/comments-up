import config from "eslint-config-prettier";
import tseslint from "typescript-eslint";
import pluginVue from "eslint-plugin-vue";
import eslintConfigPrettier from "eslint-config-prettier";

export default tseslint.config(
  ...[].concat(config),
  ...tseslint.configs.recommended,
  ...pluginVue.configs["flat/recommended"],
  {
    plugins: {
      "typescript-eslint": tseslint.plugin,
    },
    languageOptions: {
      parserOptions: {
        parser: tseslint.parser,
        project: "./tsconfig.json",
        extraFileExtensions: [".vue"],
        sourceType: "module",
      },
    },
    rules: {
      "no-unused-vars": 0,
      "no-use-before-define": 0,
      "no-shadow": 0,
      "class-methods-use-this": 0,
      "import/extensions": 0,
      "import/no-unresolved": 0,
      "import/prefer-default-export": 0,
      "vue/no-unused-vars": 0,
      "vue/multi-word-component-names": 0,
      "vue/attributes-order": 0,
      "@typescript-eslint/no-unused-vars": 0,
      "vue/attribute-hyphenation": 0,
      "vue/no-mutating-props": 0,
    },
  },
  eslintConfigPrettier
);
