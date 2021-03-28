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

* [#1](https://github.com/pheature-flags/pheature-flags/issues/1): Toggle core package
* [#2](https://github.com/pheature-flags/pheature-flags/issues/2): Toggle model package
* [#3](https://github.com/pheature-flags/pheature-flags/issues/3): In Memory toggle package
* [#4](https://github.com/pheature-flags/pheature-flags/issues/4): Toggle CRUD package
* [#5](https://github.com/pheature-flags/pheature-flags/issues/5): Toggle API package
* [#9](https://github.com/pheature-flags/pheature-flags/issues/9): Toggle UI
* [#7](https://github.com/pheature-flags/pheature-flags/issues/7): Toggle Event-Sourced package
* [#6](https://github.com/pheature-flags/pheature-flags/issues/6): DBal toggle package
* [#10](https://github.com/pheature-flags/pheature-flags/issues/10): DBal toggle infra templates
* [#8](https://github.com/pheature-flags/pheature-flags/issues/8): Toggle Cache package

**Milestone:** [Client API](https://github.com/pheature-flags/pheature-flags/milestone/2)

> Crazy fast Client API, gets enabled and disabled features for each user in a system. 

* [](): REST API
* [](): Async server
* [](): Client traffic Event-store

**Milestone:** [SDKs](https://github.com/pheature-flags/pheature-flags/milestone/3)

> Integrate Pheature flags in any distributed software system.

* [](): PHP SKD
* [](): Node SDK
* [](): Vanilla JS SDK
* [](): Android SDK
* [](): IOS SDK
* [](): Terraform SDK?

**Milestone:** [Community packages](https://github.com/pheature-flags/pheature-flags/milestone/4)

> Use Pheature flags out of the box in you favourite PHP framework.

* [](): Standalone Prototype
* [](): Laravel Package
* [](): Laravel Prototype
* [](): Symfony Bundle
* [](): Symfony Prototype
* [](): Laminas and Mezzio package
* [](): Laminas and Mezzio Prototypes
* [](): Wordpress plugin
* [](): Wordpress prototype
* [](): Drupal module
* [](): Drupal prototype

**Milestone:** [Team management system](https://github.com/pheature-flags/pheature-flags/milestone/5)

> The team management system allows managing Pheature toggles in a more structured way, by adding members and 
> permission based roles

* [](): Team core package
* [](): Team model package
* [](): In Memory team package
* [](): Team CRUD package
* [](): Team API package
* [](): Team UI
* [](): Team Event-Sourced package
* [](): DBal team package
* [](): DBal team infra templates

**Milestone:** [Security implementations](https://github.com/pheature-flags/pheature-flags/milestone/6)

> Authentication strategies to consume client API from untrusted networks.

* [](): Security core package
* [](): Security model package
* [](): Security Basic auth package
* [](): Security JWT package
* [](): Security OAuth package
