#!/bin/bash

# load env
source .env.sh

# lấy tham số repo name
REPO_NAME="$1"

# validate
if [ -z "$REPO_NAME" ]; then
  echo "❌ Missing repo name"
  echo "👉 Usage: ./delete-repo.sh <repo-name>"
  exit 1
fi

# confirm tránh xoá nhầm
read -p "⚠️ Delete repo '$REPO_NAME' ? (y/N): " confirm

if [[ "$confirm" != "y" ]]; then
  echo "❌ Cancelled"
  exit 0
fi

# call GitHub API
STATUS=$(curl -s -o /dev/null -w "%{http_code}" -X DELETE \
  -H "Authorization: token $GITHUB_TOKEN" \
  https://api.github.com/repos/$GITHUB_USERNAME/$REPO_NAME)

# handle response
if [ "$STATUS" -eq 204 ]; then
  echo "✅ Deleted repo: $REPO_NAME"
else
  echo "❌ Failed to delete repo: $REPO_NAME (HTTP $STATUS)"
fi
