#!/bin/bash
# Define la carpeta que quieres comprimir y el archivo zip de salida
FOLDER_TO_ZIP="/var/www/html/sistema/BIBLIOTECA"
OUTPUT_ZIP="backup_$(date +\%Y-\%m-\%d).zip"

# Crea el archivo zip
zip -r $OUTPUT_ZIP $FOLDER_TO_ZIP
