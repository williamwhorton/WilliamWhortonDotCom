---
title: "Treehouse Group Chat SaaS"
date: "03/31/2026"
author: "Bill Whorton"
slug: "treehouse-writeup"
screenshot-path: "images/treehouse-screen-1.png"
screenshot-alt: "Screenshot of Treehouse Group Chat"
---
### Treehouse Group Chat
[https://v0-saas-group-chat.vercel.app/](https://v0-saas-group-chat.vercel.app/)

**Github:** [https://github.com/williamwhorton/group-chat-app](https://github.com/williamwhorton/group-chat-app)

As a challenge, I decided to build a SaaS application exclusively using agents. I didn't touch any code myself, despite being sorely tempted at times. I wanted to really delve into agentic development, come up with some best practices, and see where I wanted it to fit in my process. Secondarily, I wanted some experience with things like devops, infrastructure, payment processing, and so forth. 

I used two agents. The first, and the one I usually gave the first crack at new features, was V0. As I planned on hosting via my Vercel account, this seemed the smoothest way. The second was Junie, which is an agent plugin for JetBrains IDEs, which I use and which I had already purchased credits for. My plan was initially to only use V0, but I quickly found that it would struggle with some issues and start burning through tokens quickly without making progress. Junie is much, much more efficient, and was often much better at resolving issues. Where V0 excelled, however, was in anything touching on integration. Since the code was deployed on Vercel, authenticated through Vercel, and used Supabase (recommended by Vercel) for the database, that makes sense.

I have plans to turn it into a monorepo, but the mobile version isn't ready yet.

This is the stack:
- Frontend: Next.js 16, React 19, TypeScript
- Styling: Tailwind CSS, shadcn/ui
- Testing: Jest, Testing Library, Cypress

