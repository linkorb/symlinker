<!-- Managed by https://github.com/linkorb/repo-ansible. Manual changes will be overwritten. -->
symlinker
============

Creates or updates symlinks from a config file

## Use-cases:

* Develop packages locally (composer, npm, ansible-galaxy)



## Installation:

```sh
$ git clone git@github.com:linkorb/symlinker.git
$ cd symlinker
$ composer install
```

Then update your `PATH` environment to include the `bin/` directory of the symlinker repository.


## Usage:

Create a file called `symlinker.yaml` in the root of your project, for example:

```yaml
links:
    - "~/git/my-composer-package:./vendor/my-org/my-composer-package" # PHP / Composer
    - "~/git/my-node-package:./node_modules/my-node-package" # Node / NPM
    - "~/git/ansible-role-example:./roles/example" # Ansible / Galaxy
    - "~/my-content:./my-content" # ... or any other arbitrary symlink
```

Your `symlinker.yaml` file is specific for your development environment, so be sure to add it to your `~/.gitignore` (in your home-dir, not `./.gitignore` in the repo!)

Then you can quickly (re)install the symlinks:

```sh
$ symlinker link
```

## How does it work?

symlinker loops over your configured "links". for every link it:

1. checks if the "from" directory or file exists
2. checks if the "to" symlink already exists at the target. if so, it removes it.
3. checks if "to" is a directory or file. if so, it archives the existing data to `/tmp/symlinker/archive/` + full path + date + time
4. sets up the symlink from "from" to "to".

So if you've accidentally replaced a directory with valuable files with a symlink, you can always recover it from the archive directory.

## Contributing

We welcome contributions to make this repository even better. Whether it's fixing a bug, adding a feature, or improving documentation, your help is highly appreciated. To get started, fork this repository then clone your fork.

Be sure to familiarize yourself with LinkORB's [Contribution Guidelines](/CONTRIBUTING.md) for our standards around commits, branches, and pull requests, as well as our [code of conduct](/CODE_OF_CONDUCT.md) before submitting any changes.

If you are unable to implement changes you like yourself, don't hesitate to open a new issue report so that we or others may take care of it.
## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).
By the way, we're hiring!
