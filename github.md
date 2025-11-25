<!-- /* https://github.com/Ravendam0/Aliwa-Uniform-Shop.git */ -->



<!-- /* # Initialize git in your folder -->
git init

<!-- # Add all files -->
git add .

<!-- # Commit your changes -->
git commit -m "First Commit"

<!-- # Add your GitHub repository URL -->
git remote add origin https://github.com/Ravendam0/Aliwa-Uniform-Shop.git

<!-- # Push your files to GitHub -->
git branch -M main
git push -u origin main

<!-- if i want to update the files later, you can use the following commands: -->

git add .
git commit -m "Updated frontend files"
git push 

git reset --soft HEAD~2 <!-- # Go back 2 commits, keeping changes staged -->
git reset --hard HEAD^  <!-- # Go back 1 commit, destroying all changes --->

git reset --mixed <commit-hash>

<!-- Anything you want to ignore is put in afile known as .gitignore -->

If a file has already been committed to the repository, adding it to .gitignore will not ignore it. Git will continue to track it.

To fix this, you must tell Git to stop tracking the file, but keep it on your local filesystem, using the git rm --cached command:


git rm --cached <filename>
git commit -m "Stop tracking the sensitive file"

git stash save "Work on login feature, switching to hotfix"

# Or the short form:

git stash