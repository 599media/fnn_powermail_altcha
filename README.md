# fnn_powermail_altcha

**Title:** 599media Powermail Altcha

**Description:**
Altcha spam protection for Powermail forms. This extension integrates the Altcha library to add challenge/response spam protection to Powermail forms.

After installation, a new element called “Altcha Spam Protection” is available in Powermail among the available field types.

To activate spam protection, simply add this field to the desired form. As soon as a Powermail form contains an active “Altcha Spam Protection” field, the Altcha widget is automatically rendered at exactly this point in the frontend.

The visitor then sees the Altcha widget. This additional validation effectively prevents automated bots, while the user experience remains as smooth as possible for real users.



---

## Overview

- **Extension-Key:** `fnn_powermail_altcha`
- **Version:** 10.0.0
- **Author:** 599media Dev-Team (599media GmbH)
- **License:** MIT
- **Category:** Service
- **Dependencies:**
    - TYPO3 ≥ 10.4.0 – 13.4.99
    - PHP ≥ 7.4.0 – 8.4.99
    - Powermail ≥ 8.5.0 – 13.99.99

---

## Installation

### Install extension via ZIP file ###
1. Download ZIP file
2. Log into your TYPO3 backend
3. Go to Extension Manager module
4. Press the upload button on the top bar
5. Select the ZIP file and upload it. If you want to overwrite an existing extension installation, activate the checkbox.
6. Go to your rootpage, open the TypoScript record and add 'Powermail Altcha' to 'include TypoScript sets'
7. Clear backend cache

### Install extension via composer command ###
1. Go to your folder where the root composer.json file is located
2. Type: composer require fnn/fnn-powermail-altcha to get the latest version that runs on your TYPO3 version.
3. Run 'Analyze database' and create the 'table tx_fnnpowermailaltcha_domain_model_challenge'
4. Go to your rootpage, open the TypoScript record and add 'Powermail Altcha' to 'include TypoScript sets'
5. Clear backend cache

---

## Configuration

### TypoScript ###

#### Constants ####
`Configuration/TypoScript/constants.typoscript`
```
plugin.tx_fnn_powermail_altcha {
    # HMAC key (random character string)
    **IMPORTANT: For security, you must generate a unique key for your installation and replace the placeholder below.**
    hmacKey = K70ne+Ej;5NjJy&}09Dj~Kxr{Z

    # Maximum number for the challenge
    maxNumber = 999999

    # Expiry time in seconds (0 = 24 hours)
    expires = 0

    # Hide Altcha logo (0 = show, 1 = hide)
    hideLogo = 0

    # Hide altcha footer (0 = show, 1 = hide)
    hideFooter = 0

    # Validierungstyp: off, onfocus, onload, onsubmit
    validation = off

    # You can configure Altcha messages yourself at this point.
    labels {
        error     = LLL:EXT:fnn_powermail_altcha/Resources/Private/Language/locallang.xlf:altchaWidget.error
        expired   = LLL:EXT:fnn_powermail_altcha/Resources/Private/Language/locallang.xlf:altchaWidget.expired
        label     = LLL:EXT:fnn_powermail_altcha/Resources/Private/Language/locallang.xlf:altchaWidget.label
        verified  = LLL:EXT:fnn_powermail_altcha/Resources/Private/Language/locallang.xlf:altchaWidget.verified
        verifying = LLL:EXT:fnn_powermail_altcha/Resources/Private/Language/locallang.xlf:altchaWidget.verifying
        waitAlert = LLL:EXT:fnn_powermail_altcha/Resources/Private/Language/locallang.xlf:altchaWidget.waitAlert
    }
}
```

#### Setup ####
`Configuration/TypoScript/setup.typoscript`
```
plugin.tx_powermail {
    view {
        # The Altcha widget is rendered via a fluid partial.
        # A low number was selected to enable overwriting without major adjustments.
        partialRootPaths {
            5 = EXT:fnn_powermail_altcha/Resources/Private/Templates/Powermail/Partials/
        }
    }
    settings {
        setup {
            spamshield {
                methods {
                    2025 {
                        # The spam check can be activated or deactivated here.
                        _enable = 1
                    }
                }
            }
        }
    }
}
```

### TSConfig ###
The required TSConfig is loaded automatically after installation of the extension.

---

## Changelog ##
All changes can be found in the ChangeLog file. Version 10.0.0 (initial release) and future updates are documented there.

---

## Support & Contact ##
- Author: Dev-Team @ 599media GmbH
- E-Mail: info@599media.de
- Homepage: https://599media.de

---

## License (MIT) ##
The complete license information can be found here.

Additional license information:

This extension contains the ***altcha-org/altcha*** library, which is licensed under a separate MIT license.
The complete license text of the Altcha library can be found in the file ***Vendor/altcha-lib-php/LICENSE.txt*** .

---

## Functionality (simplified) ##
```
                         ┌─────────────────────────┐
                         │     TYPO3 Frontend      │
                         │                         │
                         │  Powermail form page    │
                         └───────────┬─────────────┘
                                     │
                                     ▼
             ┌────────────────────────────────────────────────┐
             │  Render partial „AltchaSpamProtection.html“    │
             │                                                │
             │  • execute AltchaSpamProtectionViewHelper      │
             │    AltchaService::createChallenge()            │
             └───────────────────────┬────────────────────────┘
                                     │
                                     ▼
             ┌────────────────────────────────────────────────┐
             │  AltchaService                                 │
             │  • generates random number (maxNumber)         │
             │  • calculates HMAC signature (hmacKey)         │
             │  • Saves record in DB (Challenge, expires)     │
             │                                                │
             │  challenge + signature back to Widget          │
             └───────────────────────┬────────────────────────┘
                                     │
                                     ▼
             ┌────────────────────────────────────────────────┐
             │  Browser (Client)                              │
             │  ┌──────────────────────────────────────────┐  │
             │  │ altcha.min.js:                           │  │
             │  │  • displays interactive captcha          │  │
             │  │  • User solves task                      │  │
             │  │  • Payload is packed (response)          │  │
             │  └──────────────────────────────────────────┘  │
             │   Form submit with Altcha payload              │
             └───────────────────────┬────────────────────────┘
                                     │
                                     ▼
             ┌────────────────────────────────────────────────┐
             │  Powermail Spamshield (AltchaMethod)           │
             │  • execute AltchaService->verifySolution()     │
             └───────────────────────┬────────────────────────┘
                                     │
                                     ▼
             ┌────────────────────────────────────────────────┐
             │  AltchaService                                 │
             │  • validate payload with                       │
             │    Altcha::verifySolution()                    │
             │                                                │
             │  result back to AltchaMethod                   │
             └────────────────────────────────────────────────┘
```

---

## Note ##
Ignore the folder Vendor/altcha-lib-php. Only the complete source code of the external Altcha library is located there in order to bypass a Composer installation on the TER platform. All further adjustments and configurations refer to the Classes/ directory and the TYPO3-specific configuration files.

With a Composer installation, the library is installed by Composer.

