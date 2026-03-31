import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const staticSrcPath = path.join(__dirname, '.next/static');
const staticDestPath = path.join(__dirname, '.next/standalone/.next/static');

const publicSrcPath = path.join(__dirname, 'public');
const publicDestPath = path.join(__dirname, '.next/standalone/public');

function copyAssets(src, dest) {
    return fs
        .mkdir(dest, { recursive: true })
        .then(() => fs.readdir(src, { withFileTypes: true }))
        .then((items) =>
            Promise.all(
                items.map((item) => {
                    const srcPath = path.join(src, item.name);
                    const destPath = path.join(dest, item.name);
                    return item.isDirectory()
                        ? copyAssets(srcPath, destPath)
                        : fs.copyFile(srcPath, destPath);
                })
            )
        );
}

copyAssets(staticSrcPath, staticDestPath)
    .then(() => copyAssets(publicSrcPath, publicDestPath))
    .then(() => console.log('✓ Assets copied'))
    .catch((err) => {
        console.error(err);
        process.exit(1);
    });
