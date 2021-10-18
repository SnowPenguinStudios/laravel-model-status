# Changelog

All notable changes to `laravel-model-status` will be documented in this file.

## 1.1.0 - 2021-10-17
- Status Ordering
- Adding fillable to Status model to allow mass assignment
- Provide PHP v7.3+ support with testing
- Updated `::defaultStatus()` functionality to default to model specific default when there is a non-model specific default set as well.
- Updated README.md
  - Providing new documentation for Status Ordering
  - Providing documentation to show what attributes are mass assignable for Status model class
  - Added documentation of Model `::availableStatuses()` and `::defaultStatus()` functionality

## 1.0.0 - 2021-09-14
- initial release
