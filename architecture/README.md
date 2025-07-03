# Architecture Decision Records (ADRs)

This directory contains all Architecture Decision Records for the Gyroscops Compiler project. ADRs document significant architectural decisions, their context, rationale, and consequences.

## ADR Index

| ADR | Title | Status | Date | Next Review |
|-----|-------|--------|------|-------------|
| [ADR000](ADR000-adr-management-process.md) | ADR Management Process and Guidelines | Active | 2024-12-19 | 2025-12-19 |
| [ADR001](ADR001-maintenance-guidelines.md) | Gyroscops Compiler Architecture and Maintenance Guidelines | Active | 2024-12-19 | 2025-03-19 |

## Quick Reference

### ADR Status Definitions
- **Proposed**: Under discussion, not yet approved
- **Active**: Approved and currently in effect
- **Superseded**: Replaced by a newer ADR
- **Deprecated**: No longer recommended but not yet replaced
- **Rejected**: Considered but not approved

### Creating a New ADR

1. **Identify the need** for an architectural decision
2. **Get the next ADR number** (check the index above for the highest number)
3. **Use the template** provided in [ADR000](ADR000-adr-management-process.md)
4. **Follow the naming convention**: `ADR{number}-{short-title}.md`
5. **Start with status "Proposed"**
6. **Seek team review** and feedback
7. **Get approval** from project maintainers
8. **Update status to "Active"** and update this index

### ADR Template

```markdown
# Architecture Decision Record (ADR) - {Title}

## ADR{XXX}: {Title}

**Status:** Proposed  
**Date:** {YYYY-MM-DD}  
**Authors:** {Author Names}  
**Reviewers:** {Reviewer Names}  

---

## Context

{Describe the context and problem statement}

---

## Decision

{Describe the architectural decision and rationale}

---

## Consequences

### Positive
{List positive consequences}

### Negative
{List negative consequences}

---

## Compliance

{Implementation guidelines if applicable}

---

## Review and Updates

**Next Review Date:** {YYYY-MM-DD}

---

## References

{External links and related documents}
```

## Guidelines

- **Read ADR000** first to understand the ADR management process
- **Follow the established format** and template
- **Keep ADRs focused** on significant architectural decisions
- **Update this index** when creating, updating, or changing ADR status
- **Review ADRs regularly** according to their review schedule

## Resources

- [ADR000: ADR Management Process](ADR000-adr-management-process.md) - Complete guidelines for managing ADRs
- [Architecture Decision Records (ADRs)](https://adr.github.io/) - Community resources
- [ADR Tools](https://github.com/npryce/adr-tools) - Command-line tools for ADRs

---

**Last Updated:** 2024-12-19  
**Maintainer:** Development Team
