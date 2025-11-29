# Changelog

All notable changes to this project will be documented in this file.

## [1.2.0]

### Added
- **Conditional Auto-Reply Logic:** Auto-reply emails can now be sent only when a specific form field matches a configured value (e.g. only send when `inquiry_type` is `support`).
- New configuration fields in the Control Panel to enable conditional replies, choose the conditional field, and define the expected value.

### Changed
- Updated listener logic to respect conditional settings and skip auto-replies when the condition is not met.

---

## [1.1.1]

### Fixed
- Fix: container issue by setting default as `assets` (closes #3).

---

## [1.1.0]

### Fixed
- Fix Navigation Link error in Control Panel and improve error handling (see #1).

---

## [1.0.0]

### Added
- Initial release! 🎉
