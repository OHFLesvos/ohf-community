import jsQR from 'jsqr'

export function readQRcodeFromImage(imageData) {
    const code = jsQR(imageData.data, imageData.width, imageData.height, {
        inversionAttempts: "dontInvert"
    });
    if (code && code.data.trim().length > 0) {
        return code.data
    }
    return null
}
