
### Generator-Z-Dist-Implementer


#### Description

This is a small php script that set's up server-specific configuration files
based on template (*.dist) files. Inspired by the Symfony2 Framework's
parameters.yml.dist file.

Upon running the generator in your project's root folder, you'll be prompted to
provide your relative setup folder path, where the files will be installed.
(The default path is: "web/setup")

This way after deploying an application the first time, you can visit the
"setup/" url, where you can create server-specific files based on a template.

These template files must end in ".dist", and heir non-dist pair must be ignored
by source control. These dist files 

For more details and built-in examples, visit the generated setup folder.


#### Installation and Usage

run `npm install generator-z-dist-implementer -g`

After that, you can run `yo z-dist-implementer` from a project to start the
generator.


#### TODO
- make it work with a capifony deployment process
