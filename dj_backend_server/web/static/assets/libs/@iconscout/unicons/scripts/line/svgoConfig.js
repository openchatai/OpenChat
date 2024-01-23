module.exports = {
  plugins: [
    {
      removeAttrs: {
        attrs: ['(path|rect|circle|polygon|line|polyline|g|ellipse)']
      }
    },
    {
      removeTitle: true
    },
    {
      removeStyleElement: true
    },
    {
      removeComments: true
    },
    {
      removeDesc: true
    },
    {
      removeUselessDefs: true
    },
    {
      cleanupIDs: {
        remove: true,
        prefix: 'svgicon-'
      }
    },
    {
      convertShapeToPath: true
    }
  ]
}