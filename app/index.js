'use strict';
var yeoman = require('yeoman-generator');
var chalk = require('chalk');
var yosay = require('yosay');

module.exports = yeoman.generators.Base.extend({
  initializing: function () {
    this.pkg = require('../package.json');
  },

  prompting: function () {
    var done = this.async();

    // Have Yeoman greet the user.
    this.log(yosay(
      'Welcome to the ' + chalk.red('ZDistImplementer') + ' generator!'
    ));

    var prompts = [{
      type: 'input',
      name: 'publicDirectory',
      message: 'Please provide the folder\'s path you want to generate the code in.',
      default: 'web/setup'
    }];

    this.prompt(prompts, function (props) {
      this.publicDirectory = props.publicDirectory;

      done();
    }.bind(this));
  },

  writing: {
    app: function () {
      // this.fs.copy(
      //   this.templatePath('_package.json'),
      //   this.destinationPath(this.publicDirectory + '/package.json')
      // );
      // this.fs.copy(
      //   this.templatePath('_bower.json'),
      //   this.destinationPath(this.publicDirectory + '/bower.json')
      // );
    },

    projectfiles: function () {
      this.fs.copy(
        this.templatePath('_config.php'),
        this.destinationPath(this.publicDirectory + '/config.php')
      );
      this.fs.copy(
        this.templatePath('_index.php'),
        this.destinationPath(this.publicDirectory + '/index.php')
      );
      this.fs.copy(
        this.templatePath('_example.html.dist'),
        this.destinationPath(this.publicDirectory + '/example.html.dist')
      );
      this.fs.copy(
        this.templatePath('_example.yaml.dist'),
        this.destinationPath(this.publicDirectory + '/example.yaml.dist')
      );
    }
  },

  install: function () {
    // this.installDependencies({
    //   skipInstall: this.options['skip-install']
    // });
  }
});
