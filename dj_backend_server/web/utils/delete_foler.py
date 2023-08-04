import os
import shutil

def delete_folder(folder_path):
  if os.path.exists(folder_path):
    shutil.rmtree(folder_path)
    print(f'{folder_path} folder deleted successfully!')
  else:
    print(f'{folder_path} folder does not exist!')