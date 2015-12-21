# Nested Sets Change Log

This project follows [Semantic Versioning](CONTRIBUTING.md).

## Proposals

We do not give estimated times for completion on `Accepted` Proposals.

- [Accepted](https://github.com/cartalyst/nested-sets/labels/Accepted)
- [Rejected](https://github.com/cartalyst/nested-sets/labels/Rejected)

---

### v3.0.0 - 2015-03-02

- Refactored to use a trait instead of a base node class.

### v2.0.3 - 2015-02-25

`FIXED`

- An issue when used with PostgreSQL.

`REVISED`

- Switched to PSR-2, PSR-4.

### v2.0.2 - 2014-01-07

`ADDED`

- `getDepth` method.

`FIXED`

- A bug when using other than mysql database drivers.

### v2.0.1 - 2013-11-27

`ADDED`

- `allFlat` method. Returns a collection containing a flat array of all nodes.

`FIXED`

- Fixed a bug introduced by an Eloquent change. [#52]

### v2.0.0 - 2013-08-22

- Initial release.
