const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/assets/')
  .setPublicPath('/assets')
  .cleanupOutputBeforeBuild()
  .autoProvidejQuery()
  .enableSourceMaps(!Encore.isProduction())
  .enableLessLoader()
  .enableSassLoader()
  .disableSingleRuntimeChunk()
  .copyFiles({
    from: './assets/img',
    pattern: /\.(png|jpg|jpeg)$/,
    to: 'img/[path][name].[ext]'
  })
  .addEntry('frontend', './assets/frontend.js')
  .addEntry('backend', './assets/backend.js')
;

module.exports = Encore.getWebpackConfig();
