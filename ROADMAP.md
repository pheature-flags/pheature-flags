# Pheature Flags Development Roadmap

**Get full control over your application releases**

The Pheature flags release management system that allows to have full control over different features running
in a web application, or other software system. It separates release process from deployment, 
applies custom rollout strategies based in segments, allows applying instant rollbacks to failing features, 
reduces the technical debt on complex business experiments, and helps both developers and managers building trust 
between them.

## What do we need?

This is our open roadmap, here are listed our current work in progress, todos, and general project development status.
In the next [milestones](https://github.com/pheature-flags/pheature-flags/milestones), you can find the more important things where you can contribute, also we have created 
an [open kanvan board](https://github.com/pheature-flags/pheature-flags/projects/1) where are every [issue](https://github.com/pheature-flags/pheature-flags/issues) and each status.
Please check out our [contribution guidelines](https://github.com/pheature-flags/pheature-flags/blob/main/CONTRIBUTING.md) to help us in any way, and don't hesitate to ask everything, 
we are wiling to hear you.

## Milestones

**Milestone:** [Toggle management system](https://github.com/pheature-flags/pheature-flags/milestone/1)

> The toggle system allows both developers and managers setting up features enabled or disabled, and applying 
> different rollout strategies based in segmentation and environments.

- [ ] [#1](https://github.com/pheature-flags/pheature-flags/issues/1): Toggle core package
- [ ] [#2](https://github.com/pheature-flags/pheature-flags/issues/2): Toggle model package
- [ ] [#3](https://github.com/pheature-flags/pheature-flags/issues/3): In Memory toggle package
- [ ] [#4](https://github.com/pheature-flags/pheature-flags/issues/4): Toggle CRUD package
- [ ] [#5](https://github.com/pheature-flags/pheature-flags/issues/5): Toggle API package
- [ ] [#9](https://github.com/pheature-flags/pheature-flags/issues/9): Toggle UI
- [ ] [#7](https://github.com/pheature-flags/pheature-flags/issues/7): Toggle Event-Sourced package
- [ ] [#6](https://github.com/pheature-flags/pheature-flags/issues/6): DBal toggle package
- [ ] [#10](https://github.com/pheature-flags/pheature-flags/issues/10): DBal toggle infra templates
- [ ] [#8](https://github.com/pheature-flags/pheature-flags/issues/8): Toggle Cache package

**Milestone:** [Client API](https://github.com/pheature-flags/pheature-flags/milestone/2)

> Crazy fast Client API, gets enabled and disabled features for each user in a system. 

- [ ] [#11](https://github.com/pheature-flags/pheature-flags/issues/11): REST API
- [ ] [#12](https://github.com/pheature-flags/pheature-flags/issues/12): Async server
- [ ] [#13](https://github.com/pheature-flags/pheature-flags/issues/13): Client traffic Event-store

**Milestone:** [SDKs](https://github.com/pheature-flags/pheature-flags/milestone/3)

> Integrate Pheature flags in any distributed software system.

- [ ] [#14](https://github.com/pheature-flags/pheature-flags/issues/14): PHP SKD
- [ ] [#15](https://github.com/pheature-flags/pheature-flags/issues/15): Node SDK
- [ ] [#16](https://github.com/pheature-flags/pheature-flags/issues/16): Vanilla JS SDK
- [ ] [#17](https://github.com/pheature-flags/pheature-flags/issues/17): Android SDK
- [ ] [#18](https://github.com/pheature-flags/pheature-flags/issues/18): IOS SDK

**Milestone:** [Community packages](https://github.com/pheature-flags/pheature-flags/milestone/4)

> Use Pheature flags out of the box in you favourite PHP framework.

- [ ] [#19](https://github.com/pheature-flags/pheature-flags/issues/19): Standalone Prototype
- [ ] [#20](https://github.com/pheature-flags/pheature-flags/issues/20): Laravel Package
- [ ] [#21](https://github.com/pheature-flags/pheature-flags/issues/21): Laravel Prototype
- [ ] [#22](https://github.com/pheature-flags/pheature-flags/issues/22): Symfony Bundle
- [ ] [#23](https://github.com/pheature-flags/pheature-flags/issues/23): Symfony Prototype
- [ ] [#24](https://github.com/pheature-flags/pheature-flags/issues/24): Laminas and Mezzio package
- [ ] [#25](https://github.com/pheature-flags/pheature-flags/issues/25): Laminas and Mezzio Prototypes

**Milestone:** [Security implementations](https://github.com/pheature-flags/pheature-flags/milestone/6)

> Authentication strategies to consume client API from untrusted networks.

- [ ] [#26](https://github.com/pheature-flags/pheature-flags/issues/26): Security core package
- [ ] [#27](https://github.com/pheature-flags/pheature-flags/issues/27): Security model package
- [ ] [#28](https://github.com/pheature-flags/pheature-flags/issues/28): Security Basic auth package
- [ ] [#29](https://github.com/pheature-flags/pheature-flags/issues/29): Security JWT package
- [ ] [#30](https://github.com/pheature-flags/pheature-flags/issues/30): Security OAuth package

**Milestone:** [Team management system](https://github.com/pheature-flags/pheature-flags/milestone/5)

> The team management system allows managing Pheature toggles in a more structured way, by adding members and 
> permission based roles

- [ ] [#31](https://github.com/pheature-flags/pheature-flags/issues/31): Team core package
- [ ] [#32](https://github.com/pheature-flags/pheature-flags/issues/32): Team model package
- [ ] [#33](https://github.com/pheature-flags/pheature-flags/issues/33): In Memory team package
- [ ] [#34](https://github.com/pheature-flags/pheature-flags/issues/34): Team CRUD package
- [ ] [#35](https://github.com/pheature-flags/pheature-flags/issues/35): Team API package
- [ ] [#36](https://github.com/pheature-flags/pheature-flags/issues/36): Team UI
- [ ] [#37](https://github.com/pheature-flags/pheature-flags/issues/37): Team Event-Sourced package
- [ ] [#38](https://github.com/pheature-flags/pheature-flags/issues/38): DBal team package
- [ ] [#39](https://github.com/pheature-flags/pheature-flags/issues/39): DBal team infra templates
