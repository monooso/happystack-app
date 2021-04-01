#!bin/sh

is_dirty() {
    if [ -z "$(git status --porcelain=v1 2>/dev/null)" ]; then
        return 1
    else
        return 0
    fi
}

# Check that we are on the correct branch
if [ "$(git rev-parse --abbrev-ref HEAD)" != 'main' ]; then
    echo 'You must run this script from the "main" branch. Aborting.'
    exit 1
fi

# Abort if there are uncommitted changes
if is_dirty; then
    echo 'You have uncommitted changes. Aborting.'
    exit 1
fi

# Ensure that we've pushed everything
git push origin main

# Switch to the production branch, and merge the changes from `main`
# The `main` branch is our source of truth
git checkout production
git merge -s recursive -X theirs --ff --commit -m 'Merge latest changes for release' main

# Build the production assets
npm run production

# If the assets have changed, commit them
if is_dirty; then
    git commit -am 'Build production assets'
fi

# DEPLOY ALL THE THINGS!
git push origin production

echo '⚡️ Pushed to production'

# Return to main
git checkout main
