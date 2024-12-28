# A Basic Crypto Coin

A basic PHP crypto coin for use in the BrickMMO Smart City Development Platform (SCDP).

The following resources were used as research for this project

- https://dev.to/enter?state=new-user&bb=123191
- https://www.youtube.com/watch?v=2FBHiz7ANEI
- https://www.youtube.com/watch?v=_mqDUSg_29k
- https://www.youtube.com/watch?v=QkBbs5JRQ-k
- https://www.youtube.com/watch?v=HneatE69814
- https://bitcoin.org/bitcoin.pdf

The crypto coin will be named [Loot](https://loot.brickmmo.com/) and has [branding guidelines](https://branding.brickmmo.com/loot).

## Nodes

The first three genesis nodes are available at:

- https://alpha.loot.brickmmo.com
- https://bravo.loot.brickmmo.com
- https://charlie.loot.brickmmo.com

## Node Installation

To setup a new node follow these steps:

1. Choose a new domain/subdomain to host the node.
2. Setup hosting for this new domain/subdomain. The hosting will require PHP and mod_rewrite.
3. Upload this repo to the new hosting public folder. You do not need to include the following files:
  - `.gitignore`
  - `.env.sample`
  - `README.md`
4. Edit the `.env` file with the new domain/subdomain and node name.
5. Add the following cron job to your server and run every five minuites minute:

```
wget -qO- https://<DOMAIN></cron/find-nodes >/dev/null 2>&1
```

6. Open `https://<YOUR_DOMAIN>` and https://alpha.loot.brickmmo.com/ and wait for your new node to be registered.

---

## Project Stack

This project uses vanilla [PHP](https://php.net) and [W3.CSS](https://www.w3schools.com/w3css).

<img src="https://console.codeadam.ca/api/image/w3css" width="60"> <img src="https://console.codeadam.ca/api/image/php" width="60">

---

## Repo Resources

* [BrickMMO](https://www.brickmmo.com/)
* [Loot](https://loot.brickmmo.com/)

<a href="https://brickmmo.com">
<img src="https://cdn.brickmmo.com/images@1.0.0/brickmmo-logo-coloured-horizontal.png" width="200">
</a>

