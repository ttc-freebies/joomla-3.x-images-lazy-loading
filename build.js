const { copy, exists, mkdir, readFile, writeFile } = require('fs-extra');
const util = require('util');
const rimRaf = util.promisify(require('rimraf'));
const { version } = require('./package.json');

(async function exec() {
  await rimRaf('./dist');
  await rimRaf('./package');
  await copy('./src', './package');

  if (!(await exists('./dist'))) {
    await mkdir('./dist');
  }

  let xml = await readFile('./package/imageslazyloading.xml', { encoding: 'utf8' });
  xml = xml.replace('{{version}}', version);

  await writeFile('./package/imageslazyloading.xml', xml, { encoding: 'utf8' });

  // Package it
  const zip = new (require('adm-zip'));
  zip.addLocalFolder('package', false);
  zip.writeZip(`dist/plg_images_lazy_loading_${version}.zip`);

  await rimRaf('./docs/dist');
  await copy('./dist', './docs/dist');

  // Update the version, docs
  ['docs/_coverpage.txt', 'docs/installation.txt', 'docs/component.txt'].forEach(async file => {
    let cont = await readFile(file, { encoding: 'utf8' });
    cont = cont.replace(/{{version}}/g, version);
    cont = cont.replace(/{{download}}/g, `[Download v${version}](dist/plg_images_lazy_loading_${version}.zip ':ignore')`);
    cont = cont.replace(/{{download2}}/g, `[component addlazyloading v${version}](https://ttc-freebies.github.io/com_addlazyloading/com_addlazyloading_1.0.0.zip ':ignore')`);

    //{{download2}}

    const ext = file === 'docs/update.txt' ? '.xml' : '.md';
    await writeFile(file.replace('.txt', ext), cont, { encoding: 'utf8' });
  })
})();
