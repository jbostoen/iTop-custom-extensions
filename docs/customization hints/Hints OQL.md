
# Escape characters

The escape character is already supported, but must be escaped itself.

```
SELECT Ticket WHERE title LIKE '%\_%'
```

Will throw a parse error.

But:

```
SELECT Ticket WHERE title LIKE '%\\_%'
```

Will work as expected.

( source: https://sourceforge.net/p/itop/tickets/1828/ - thanks Pierre Goiffon )