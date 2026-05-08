#!/bin/bash

# Directorio donde están los archivos ZIP
ZIP_DIR="."

# Directorio donde restaurar
RESTORE_DIR="/"

# Encontrar el archivo ZIP más reciente basado en el nombre de formato backup_YYYY-MM-DD.zip
ZIP_FILE=$(ls "$ZIP_DIR"/backup_*.zip | sort -t_ -k2 -r | head -n 1)

# Verificar si se encontró un archivo ZIP
if [ -z "$ZIP_FILE" ]; then
    echo "No se encontró ningún archivo ZIP en el directorio $ZIP_DIR"
    exit 1
fi

echo "Archivo ZIP más reciente encontrado: $ZIP_FILE"

# Crear el directorio de restauración si no existe
mkdir -p "$RESTORE_DIR"

# Restaurar el archivo ZIP
echo "Restaurando $ZIP_FILE en $RESTORE_DIR..."
unzip -o "$ZIP_FILE" -d "$RESTORE_DIR"

# Verificar si la restauración fue exitosa
if [ $? -eq 0 ]; then
    echo "Restauración completada correctamente."
else
    echo "Error durante la restauración."
    exit 1
fi
