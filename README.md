# LabelGrup Networks, official PrestaShop Partner

![LabelGrup Logo](logo.png)

Module for PrestaShop 1.6.1.X and 1.7.X to fix CVE-2022-36408 / CVE-2022-31181 vulnerability (Chain SQL Injection)

For further information, check the following links: 
- CVE: https://nvd.nist.gov/vuln/detail/CVE-2022-36408
- CVE (GitHub): https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2022-31181
- GitHub: https://github.com/PrestaShop/PrestaShop/security/advisories/GHSA-hrgx-p36p-89q4
- PrestaShop: https://build.prestashop.com/news/major-security-vulnerability-on-prestashop-websites/

**Instructions:**

 1. Download the latest release from this repository.
 2. Install the downloaded ZIP as a normal addon, this will replace/copy the needed files to your current PrestaShop.
 3. Be aware: If you remove the addon, your PrestaShop will be reverted to its original state, exposing the vulnerability again.

**For PrestaShop 1.6 users:**

- This addon is not compatible with PrestaShop 1.6.0.X, the minimal supported version is 1.6.1.0.
- If you remove the addon, a folder named "cvepatches" will remain in your "/classes" path. Please, delete the "cvepatches" folder once the addon is uninstalled.

Visit our website:
https://www.labelgrup.com
