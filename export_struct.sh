#!/bin/bash

# =========================
# Validate input
# =========================
if [ -z "$1" ]; then
  echo "❌ Usage: ./export_struct.sh Modules/User"
  exit 1
fi

TARGET_DIR="$1"

if [ ! -d "$TARGET_DIR" ]; then
  echo "❌ Directory not found: $TARGET_DIR"
  exit 1
fi

# =========================
# Output file inside target folder
# =========================
OUTPUT_FILE="$TARGET_DIR/structure.md"

# Clear file
echo "# 📦 Structure: $TARGET_DIR" > "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# =========================
# Tree generator function
# =========================
generate_tree() {
  local dir="$1"
  local prefix="$2"

  local items=()
  while IFS= read -r item; do
    items+=("$item")
  done < <(ls -A "$dir" 2>/dev/null)

  local count=${#items[@]}
  local i=0

  for item in "${items[@]}"; do
    i=$((i+1))
    local path="$dir/$item"

    if [ $i -eq $count ]; then
      echo "${prefix}└── $item" >> "$OUTPUT_FILE"
      new_prefix="${prefix}    "
    else
      echo "${prefix}├── $item" >> "$OUTPUT_FILE"
      new_prefix="${prefix}│   "
    fi

    if [ -d "$path" ]; then
      generate_tree "$path" "$new_prefix"
    fi
  done
}

# =========================
# Run export
# =========================
echo "\`\`\`" >> "$OUTPUT_FILE"
echo "$(basename "$TARGET_DIR")/" >> "$OUTPUT_FILE"

generate_tree "$TARGET_DIR" ""

echo "\`\`\`" >> "$OUTPUT_FILE"

# =========================
# Done
# =========================
echo "✅ Exported structure to: $OUTPUT_FILE"