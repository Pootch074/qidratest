// postcss.config.js
import postcssUrl from 'postcss-url';

export default {
  plugins: [
    postcssUrl({
      url: 'rebase',
    }),
  ],
};