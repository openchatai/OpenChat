module.exports = {
  comment: 'This is the settings file for IconScout SVG Compression.',
  pretty: true,
  indent: 2,
  floatPrecision: 3,
  plugins: [
    {
      removeDoctype: true,
    },
    {
      removeXMLProcInst: true,
    },
    {
      removeComments: true,
    },
    {
      removeMetadata: true,
    },
    {
      removeXMLNS: false,
    },
    {
      removeEditorsNSData: true,
    },
    {
      cleanupAttrs: false,
    },
    {
      inlineStyles: {
        onlyMatchedOnce: false,
      },
    },
    {
      minifyStyles: true,
    },
    {
      convertStyleToAttrs: true,
    },
    {
      cleanupIDs: true,
    },
    {
      prefixIds: false,
    },
    {
      removeRasterImages: true,
    },
    {
      removeUselessDefs: true,
    },
    {
      cleanupNumericValues: true,
    },
    {
      cleanupListOfValues: true,
    },
    {
      convertColors: true,
    },
    {
      removeUnknownsAndDefaults: true,
    },
    {
      removeNonInheritableGroupAttrs: true,
    },
    {
      removeUselessStrokeAndFill: true,
    },
    {
      removeViewBox: false,
    },
    {
      cleanupEnableBackground: false,
    },
    {
      removeHiddenElems: false,
    },
    {
      removeEmptyText: true,
    },
    {
      convertShapeToPath: false,
    },
    {
      moveElemsAttrsToGroup: false,
    },
    {
      moveGroupAttrsToElems: false,
    },
    {
      collapseGroups: true,
    },
    {
      convertPathData: false,
    },
    {
      convertTransform: true,
    },
    {
      removeEmptyAttrs: true,
    },
    {
      removeEmptyContainers: true,
    },
    {
      mergePaths: true,
    },
    {
      removeUnusedNS: true,
    },
    {
      sortAttrs: true,
    },
    {
      removeTitle: true,
    },
    {
      removeDesc: true,
    },
    {
      removeDimensions: false,
    },
    {
      removeAttrs: false,
    },
    {
      removeElementsByAttr: false,
    },
    {
      addClassesToSVGElement: false,
    },
    {
      removeStyleElement: true,
    },
    {
      removeScriptElement: true,
    },
    {
      addAttributesToSVGElement: false,
    },
  ],
}
