const purgecss = require('@fullhuman/postcss-purgecss');
const cssnano = require('cssnano');
const isProduction = process.env.NODE_ENV === 'production';

const productionBuild = [
  require('tailwindcss'),
  require('autoprefixer'),
  cssnano({ preset: 'default' }),
  purgecss({
    content: ['../**/*.php', '../*.php'],
    defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
  }),
];

const devBuild = [
  require('tailwindcss'),
];

module.exports = {
    plugins: isProduction ? productionBuild : devBuild,
};
