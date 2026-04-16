# 📸 Automated EXIF-to-Taxonomy Cinematic Watermarker

[![WordPress Version](https://img.shields.io/badge/WordPress-5.8%2B-blue.svg)](https://wordpress.org)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-8892bf.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL%20v2-green.svg)](LICENSE)
<a href="https://hits.sh/github.com/sapthesh/Automated-EXIF-Watermarker/"><img alt="Hits" src="https://hits.sh/github.com/sapthesh/Automated-EXIF-Watermarker.svg?view=today-total&color=fe7d37"/></a>

**Automated EXIF-to-Taxonomy Cinematic Watermarker** is a powerful, zero-configuration workflow tool designed specifically for professional photographers. It automates the tedious task of indexing camera metadata and adds a sophisticated, minimalist watermark to web-ready images.

---

## ✨ Key Features

-   **🔍 Automatic EXIF Extraction:** Immediately pulls Camera Make, Lens Model, Focal Length, and ISO from uploaded images.
-   **📈 SEO-Ready Taxonomies:** Programmatically registers and assigns EXIF data to custom taxonomies, making your technical settings indexable by search engines and searchable by users.
-   **🎬 Cinematic Watermarking:** Uses the PHP GD library to burn a minimalist text overlay (Camera | Lens | Settings) onto the `large` image size.
-   **🛡️ Original File Protection:** Your original high-resolution uploads (`full` size) remain 100% untouched and un-watermarked.
-   **⚡ Performance-Optimized:** Hook-based execution ensures the processing happens in the background during the standard WordPress upload flow.

---

## 🛠️ How It Works

The plugin hooks into the `wp_generate_attachment_metadata` filter, which fires the moment an image is uploaded. 

1.  **Extract:** It reads the raw binary EXIF data using PHP's native `exif_read_data()`.
2.  **Taxonomize:** It sanitizes this data and maps it to specific custom taxonomies (`aewmc_camera_make`, `aewmc_lens_model`, etc.).
3.  **Process:** It identifies the `large` generated image file and applies a semi-transparent, dynamic-sized watermark in the bottom-right corner.

---

## 🚀 Installation

1.  **Download/Clone:** Place the `automated-exif-watermarker` folder into your `/wp-content/plugins/` directory.
2.  **Asset Setup:** 
    -   Create the directory: `assets/fonts/`
    -   Place a TrueType font (.ttf) of your choice inside and rename it to `cinematic-font.ttf`.
3.  **Activate:** Navigate to the **Plugins** menu in your WordPress Admin and click **Activate**.
4.  **Verify:** Upload a photo to the Media Library. Check the "Attachment Details" for new EXIF tags and view the "Large" image preview to see the watermark.

---

## ⚙️ Technical Specifications

### Custom Taxonomies Registered
| Taxonomy Slug | Label | Example Value |
|---|---|---|
| `aewmc_camera_make` | Camera Make | Sony, Canon, Fujifilm |
| `aewmc_lens_model` | Lens Model | FE 35mm F1.4 GM |
| `aewmc_focal_length` | Focal Length | 35mm |
| `aewmc_iso` | ISO | ISO 400 |

### Requirements
-   **PHP GD Extension:** Must be enabled on your server for watermarking.
-   **Write Permissions:** Ensure your `uploads/` directory allows the plugin to modify generated image sizes.

---

## ❓ FAQ

**Q: Will this slow down my website?**  
A: No. The processing happens only once—during the initial image upload. It does not affect front-end page load speeds.

**Q: Does it work with PNG files?**  
A: Yes, it supports both JPEG and PNG. Note that many PNG files do not contain standard EXIF data.

**Q: How do I change the watermark font?**  
A: Simply replace the file at `assets/fonts/cinematic-font.ttf` with any other `.ttf` file using the same name.

---

## 🔒 Security & Privacy
This plugin treats metadata as user-supplied input. Every piece of EXIF data is passed through `sanitize_text_field()` before being committed to your database to prevent XSS or injection vulnerabilities.

---

## 📜 License
This project is licensed under the GPL v2 or later.
