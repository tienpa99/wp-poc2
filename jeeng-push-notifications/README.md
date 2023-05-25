# Test plugin before deploy:
zip the files under "trunk" folder, **make sure the zip file name is "jeeng-push-notifications"**. 


# Deployment instruction
All changes should be done in the "/trunk" folder only.
In order to deploy (to production!) you should merge the code to release.
during the deployment the pipeline will *automatically* bump the version in `trunk/readme.txt` and `/trunk/wp-jeeng.php` 
and will commit, push and tag it in git.
once done it will push the changes to SVN.
pay attention - new version will not save under "/tags" folder anymore in git, but will be kept in SVN.

in case of issues with the tag,
1. create a new tag (with new higher version and don't forget the comment with -m since the pipeline recognize only annotated tags!)
`git tag 2.0.7 -m "bump" --force`
2. push the version while avoid creating new pipeline:
 `git push origin 2.0.7 --force -o ci.skip`
 * replace 2.0.7 with new higher version!


# Push version manual from local host in case of issue with CI/CD:
# Deploy wordpress plugin to store:
1. Install svn: `brew install svn`
2. Checkout to project: `svn co --username jeeng https://plugins.svn.wordpress.org/jeeng-push-notifications`
3. Remember - all code changes should be done in the trunk folder only.
4. (DO NOT FORGET!) Bump the version in `/trunk/wp-jeeng.php` (at the comment section) and stable version in `trunk/readme.txt`.
5. To *release* the code to the store
    1. Create new version of the app under tags folder using svn cmd:  `svn cp trunk tags/0.0.0` (DON'T forget to update the version to be the same as in `/trunk/wp-jeeng.php`!!)
    2. Push (Commit) changes to wordpress svn repo: `svn ci --username jeeng -m "message of the commit - tagging version 0.0.0"`

* Use the password of "jeeng" user that used in "https://wordpress.org/plugins/" (The password is in 1password and access to msp-admin group, reset it via it@jeeng.com if needed)

The ci command will commit the changes the changes from the new tag folder.

SVN best practice by wordpress - https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/#best-practices 


