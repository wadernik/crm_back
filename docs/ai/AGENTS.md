# AGENTS.md

## Project goal

Migrate the existing Laravel application to Laravel 13 and PHP 8.5, preserving established business behavior unless an intentional change is explicitly approved.

Target architecture: modular monolith with DDD-inspired module boundaries.

Infrastructure: Docker, PostgreSQL (existing database engine, if applicable), Redis for caching, RabbitMQ for asynchronous messaging.

## Engineering rules

- Inspect existing code and relevant documentation before making changes.
- For non-trivial tasks, prepare a plan and list affected files before implementation.
- Implement only the approved scope. Do not start subsequent migration phases automatically.
- Preserve public API contracts, business rules, database semantics, and integration behavior unless explicitly instructed otherwise.
- Use `declare(strict_types=1);` in all new PHP files.
- Use explicit parameter, property, and return types wherever possible.
- Use enums for closed sets of domain values and readonly objects where immutability is appropriate.
- Use PHP 8.2+ features (readonly, enums, typed properties)
- Use Eloquent relationships properly (avoid N+1 with eager loading)
- Follow PSR-12 coding standards
- Keep Domain independent of Laravel, Eloquent, infrastructure clients, and transport protocols.
- Keep business logic out of controllers and persistence models.
- Do not introduce generic abstractions, interfaces, repositories, or domain events without a concrete architectural reason.
- Do not add or upgrade dependencies without explaining the need and compatibility implications.
- Never silently change database schemas, public API contracts, queue payloads, or message semantics.
- Do not run destructive database commands or modify production data.
- Do not commit, push, deploy, or change external systems without explicit approval.
- Do not use raw queries without protection (SQL injection)
- Do not skip eager loading (causes N+1 problems)
- Do not store sensitive data unencrypted
- Do not mix business logic in controllers
- Do not hardcode configuration values
- Do not skip validation on user input
- Do not use deprecated Laravel features
- Do not ignore queue failures

## Verification

- Run the relevant tests and static checks after each implementation task.
- Report exact commands executed and their observed results.
- Never claim a check passed if it was not executed successfully.
- If verification is blocked, explain why and identify the remaining risk.
- Review the diff for unrelated changes and unintended behavior changes.

## Completion report

For each task, report:
1. What changed and why.
2. Files affected.
3. Tests and checks executed, with results.
4. Architectural decisions and assumptions.
5. Unresolved questions and known risks.
