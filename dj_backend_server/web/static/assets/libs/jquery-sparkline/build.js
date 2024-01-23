var shell = require('shelljs');

var package = require('./package');

var files = ['header.js', 'defaults.js', 'utils.js', 'simpledraw.js', 'rangemap.js', 'interact.js', 'base.js', 'chart-line.js', 'chart-bar.js', 'chart-tristate.js', 'chart-discrete.js', 'chart-bullet.js', 'chart-pie.js', 'chart-box.js', 'vcanvas-base.js', 'vcanvas-canvas.js', 'vcanvas-vml.js', 'footer.js'];

shell.cd('src');

var src = shell.cat(files).replace(/@VERSION@/mg, package.version);

shell.cd('..');

src.to('jquery.sparkline.js');
