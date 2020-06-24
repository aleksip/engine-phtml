'use strict';

const path = require('path');

const php = {
  renderFile(templateFile, data) {
    const renderer = path.join(__dirname, 'renderer.php')
    const buffer = new Buffer.from(JSON.stringify(data));
    const cmd = 'php ' + renderer + ' --file=' + templateFile + ' --data=' + buffer.toString('base64');
    const exec = require('child_process').exec;
    return new Promise((resolve, reject) => {
      exec(cmd, (error, stdout, stderr) => {
        if (error) {
          console.warn(error);
        }
        resolve(stdout ? stdout : stderr);
      });
    });
  }
}

module.exports = php;
