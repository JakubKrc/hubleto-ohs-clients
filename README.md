# Ohs Clients

Clients (employers) of an occupational health service on Hubleto Customers: client types, audit interval, e-mail export.

Hubleto app of the Ohs set (occupational health service, Slovakia). Namespace: `Hubleto\App\External\WaiBlue\OhsClients`.
Needs: `wai-blue/hubleto-ohs-core`.

## Install into a Hubleto project

```bash
composer config repositories.wai-blue/hubleto-ohs-clients vcs <repository-url>
composer require wai-blue/hubleto-ohs-clients:dev-main
php hubleto app install "Hubleto\App\External\WaiBlue\OhsClients"
npm run build
```
