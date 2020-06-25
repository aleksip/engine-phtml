'use strict';

/*
 * PHP template pattern engine for patternlab-node
 */

const fs = require('fs-extra');
const path = require('path');

let php = require('./php');
let patternLabConfig = {};

const engine_phtml = {
  engine: php,
  engineName: 'phtml',
  engineFileExtension: '.phtml',
  expandPartials: false,

  usePatternLabConfig(config) {
    patternLabConfig = config;
  },

  renderPattern(pattern, data, partials) {
    return php.renderString(pattern.template, data);
  },

  findPartialsWithStyleModifiers(pattern) {
    return null;
  },

  findPartialsWithPatternParameters(pattern) {
    return null;
  },

  registerPartial(pattern) {
    return null;
  },

  findListItems(pattern) {
    return null;
  },

  findPartials(pattern) {
    return null;
  },

  spawnFile(config, fileName) {
    const paths = config.paths;
    const metaFilePath = path.resolve(paths.source.meta, fileName);
    try {
      fs.statSync(metaFilePath);
    } catch (err) {
      //not a file, so spawn it from the included file
      const metaFileContent = fs.readFileSync(
        path.resolve(__dirname, '..', '_meta/', fileName),
        'utf8'
      );
      fs.outputFileSync(metaFilePath, metaFileContent);
    }
  },

  spawnMeta(config) {
    this.spawnFile(config, '_00-head.phtml');
    this.spawnFile(config, '_01-foot.phtml');
  }
};

module.exports = engine_phtml;
