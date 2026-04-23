# laravel-todo — Spécifications du package

## Ce que le package fait

Permet d'attacher des todos à n'importe quel modèle Eloquent d'une application Laravel
(User, Project, Team, Invoice...) via un trait, une Facade et une API fluide.

---

## API visée

```php
// Via le trait sur le modèle
$user->todos()->create(['title' => 'Faire les courses']);
$user->todos()->pending()->get();
$user->todos()->overdue()->get();
$user->completeTodo($todo);

// Via la Facade
Todo::for($user)->create(['title' => 'Fix bug', 'due_at' => now()->addDays(2)]);
Todo::for($project)->pending()->highPriority()->get();
Todo::for($user)->completed()->count();
```

---

## Base de données — table `todos`

| Colonne          | Type        | Description                                  |
|------------------|-------------|----------------------------------------------|
| id               | ulid/uuid   |                                              |
| todoable_type    | string      | Relation polymorphique (User, Project...)    |
| todoable_id      | string      |                                              |
| title            | string      |                                              |
| notes            | text/null   | Description optionnelle                      |
| status           | enum        | pending, in_progress, completed, cancelled   |
| priority         | enum        | low, medium, high                            |
| due_at           | timestamp   | Date d'échéance (nullable)                   |
| completed_at     | timestamp   | Rempli automatiquement quand completed       |
| created_by       | FK/null     | user_id de celui qui a créé le todo          |
| timestamps       |             |                                              |

---

## Fonctionnalités à implémenter

### Trait `HasTodos`
- Relation `todos()` morphMany vers le modèle Todo interne
- Méthodes `completeTodo($todo)`, `cancelTodo($todo)`
- Boot automatique (bootHasTodos) — optionnel

### Scopes sur le modèle Todo
- `pending()` — status = pending
- `inProgress()` — status = in_progress
- `completed()` — status = completed
- `overdue()` — due_at < now() et pas completed
- `highPriority()` — priority = high
- `dueToday()` — due_at entre début et fin du jour courant

### Events à dispatcher
- `TodoCreated` — après création
- `TodoCompleted` — après completion (remplit completed_at)
- `TodoCancelled` — après annulation

### Facade `Todo`
- `Todo::for($model)` — retourne un query builder scopé
- `Todo::for($model)->create([...])`

### Commande Artisan
- `todos:prune --days=30` — supprime les todos completed/cancelled plus vieux que N jours

### Config publiable
- `status` labels (personnalisables)
- `priority` labels
- `prune_after_days` — valeur par défaut pour la commande prune

---

## Ce que le package ne fait PAS
- Pas d'interface UI
- Pas de routes exposées
- Pas de notifications (l'utilisateur écoute les events et décide)
