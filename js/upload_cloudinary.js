// js/upload_cloudinary.js
export async function uploadToCloudinary(file) {
  const CLOUD_NAME = "dgaidhktu"; 
  const UPLOAD_PRESET = "images"; 
  const formData = new FormData();
  formData.append("file", file);
  formData.append("upload_preset", UPLOAD_PRESET);

  const response = await fetch(`https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`, {
    method: "POST",
    body: formData,
  });

  if (!response.ok) throw new Error("Cloudinary upload failed");

  const data = await response.json();
  console.log("✅ Uploaded to Cloudinary:", data.secure_url);
  return data.secure_url; // Return the direct hosted image URL
}
