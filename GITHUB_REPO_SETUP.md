# GitHub Repository Setup Guide

## Current Status
Your repository is currently pointing to: `https://github.com/laravel/laravel.git`

## To Change Repository Name to "alumni"

### Option 1: If you already have a GitHub repository called "alumni"

Update the remote URL:
```bash
git remote set-url origin https://github.com/YOUR_USERNAME/alumni.git
```

Replace `YOUR_USERNAME` with your GitHub username.

### Option 2: Create a new GitHub repository and connect it

1. **Create repository on GitHub:**
   - Go to https://github.com/new
   - Repository name: `alumni`
   - Choose Public or Private
   - DO NOT initialize with README, .gitignore, or license (since you already have code)
   - Click "Create repository"

2. **Update remote URL:**
   ```bash
   git remote set-url origin https://github.com/YOUR_USERNAME/alumni.git
   ```

3. **Push your code:**
   ```bash
   git push -u origin main
   ```
   (or `git push -u origin master` if your branch is called master)

### Option 3: Use GitHub Desktop

1. Open GitHub Desktop
2. File → Options → Git
3. Check your GitHub account is connected
4. Repository → Repository Settings → Remote
5. Change the remote URL to your alumni repository

### Commands to Run:

Replace `YOUR_USERNAME` with your actual GitHub username:

```bash
# Remove old remote
git remote remove origin

# Add new remote (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/alumni.git

# Verify the change
git remote -v

# Push to new repository
git branch -M main  # Rename branch to main if needed
git push -u origin main
```

## Quick Fix Script

Run this in PowerShell (replace YOUR_USERNAME):

```powershell
$githubUsername = Read-Host "Enter your GitHub username"
git remote set-url origin "https://github.com/$githubUsername/alumni.git"
git remote -v
Write-Host "Remote updated! Make sure you have created the 'alumni' repository on GitHub first."
```


