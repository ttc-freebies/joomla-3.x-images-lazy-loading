const { copy, exists, mkdir, readFile, writeFile } = require('fs-extra');
const rimRaf = require("es6-promisify").promisify(require('rimraf'));
const { version } = require('./package.json');

(async function exec() {
  await rimRaf('./dist');
  await rimRaf('./package');
  await copy('./src', './package');

  if (!(await exists('./dist'))) {
    await mkdir('./dist');
  }

  let xml =  await readFile('./package/imageslazyloading.xml', { encoding: 'utf8' });
  xml = xml.replace('{{version}}', version);

  await writeFile('./package/imageslazyloading.xml', xml, { encoding: 'utf8' });

  // Package it
  const zip = new (require('adm-zip'));
  zip.addLocalFolder('package', false);
  zip.writeZip(`dist/plg_images_lazy_loading_${version}.zip`);
})();
