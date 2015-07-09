## FAQ

### PUT vs. PATCH {#put-vs-patch}

---

`PUT` vs `PATCH` is a delicate subject. Basically, `PUT` requests should receive all new attributes for a resource. If any attributes are missing, they should be removed. `PATCH` allows for sending through just the attributes to be updated. In order to not cause confusion, we recommend sticking with the more commonly known `PUT` HTTP verb rather than `PATCH`.