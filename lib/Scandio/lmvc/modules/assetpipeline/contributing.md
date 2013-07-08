# How to contribute

Third-party patches are essential for an awesome project. The asset pipeline started as a proof of concept trying to incorporate own ideas. We want to keep it as easy as possible to contribute changes that get things working in your environment too. There are a few guidelines that we need contributors to follow so that we can everything sorted out.

# Write your own asset pipe

Just go to the namespace `asssetpipeline\assetpipes` and check out existing pipes to figure out what is going on.
Writing an own pipe can be accomplished just by extending the `AbstractAssetPipe.php` and implementing the `process()`-method.
This function will be called and passed an argument with the path to the file in question. Process the file in any fashion necessary and return the file's content (not the path). Caching and any other logic will be handled from there on.

Finally, register your file in the `lmvc-module's` root directory's `bootstrap.php`. The only thing you have to decide on is your pipes type which is basically the url it will be accessible under. Thereby, registering a pipe with `assetpipes\CoffeescriptPipe::register(['coffee'])` will make your pipe available at `assetpipeline/coffee` including file and argument parsing.

## Getting Started

* Make sure you have a [GitHub account](https://github.com/signup/free)
* Submit a ticket for your issue, assuming one does not already exist.
  * Clearly describe the issue including steps to reproduce when it is a bug.
  * Make sure you fill in the earliest version that you know has the issue.
* Fork the repository on GitHub

## Making Changes

* Create a topic branch from where you want to base your work.
  * This is usually the master branch.
  * To quickly create a topic branch based on master; `git branch
    feature/my-addition master` then checkout the new branch with `git checkout feature/my-addition`
* Make commits of logical units.
* Check for unnecessary whitespace with `git diff --check` before committing.
* Make sure your commit messages are in the proper format.

## Submitting Changes

* Push your changes to a topic branch in your fork of the repository.
* Submit a pull request to the repository.
* Wait for us to check our your awesomeness!