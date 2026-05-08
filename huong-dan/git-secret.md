trường hợp bị dính secret : 
- git reset --soft origin/main = xóa commit, giữ code 
- git status , kiểm tra code mới 
- git add . 
- git commit -m "dữ liệu mới." 
- git push origin main


kiểm tra phục hồi code khi commit:
git reflog --oneline -10
git reset --hard 6ecf34a


crsr_b13668beb8b8138d8bbb68b04c412a9c49edcf0b6951456c646f474b6c8385de
