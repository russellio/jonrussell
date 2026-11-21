# systemd units (lawnwriter-prod-1)

Copies of what is installed on prod. Deploys do not install these; apply by hand.

- `inertia-ssr.service` → `/etc/systemd/system/inertia-ssr.service`
- `inertia-ssr.service.d/exec.conf` runs the SSR bundle with node directly, skipping the
  ~80 MB `php artisan inertia:start-ssr` wrapper process.
- `inertia-ssr.service.d/memory.conf` caps the V8 heap and the unit's cgroup memory. The box
  has 1 GB RAM; uncapped, node grew to ~300 MB over 3 days and pushed the box into swap.
  If node exceeds the cap it restarts (`Restart=always`) and Inertia falls back to
  client-side rendering meanwhile.

Install:

```sh
sudo install -m 644 inertia-ssr.service /etc/systemd/system/
sudo install -d /etc/systemd/system/inertia-ssr.service.d
sudo install -m 644 inertia-ssr.service.d/*.conf /etc/systemd/system/inertia-ssr.service.d/
sudo systemctl daemon-reload && sudo systemctl restart inertia-ssr
```
