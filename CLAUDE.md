# CRM Hospital V2 - MediCare Pro

## Project Overview
Laravel 9 hospital management CRM with modules: Reception, Medecin, Caisse, Pharmacie, Admin.
Uses MAMP (MySQL port 8889, user root/root), Tailwind CSS, Vite, Alpine.js.

## BMAD Method - AI Agent Framework

This project uses the **BMAD Method v6** (Build More, Architect Dreams) multi-agent framework.

### Available Agents (in `.bmad/agents/`)

| Agent | Name | Role |
|-------|------|------|
| `bmad-agent-analyst` | Analyst | Requirements analysis and research |
| `bmad-agent-pm` | PM (Product Manager) | PRD creation, product strategy |
| `bmad-agent-architect` | Architect | System architecture and technical design |
| `bmad-agent-dev` | Amelia (Developer) | Story execution and code implementation |
| `bmad-agent-qa` | QA Engineer | Testing strategy and quality assurance |
| `bmad-agent-sm` | Scrum Master | Sprint planning and agile ceremonies |
| `bmad-agent-ux-designer` | UX Designer | User experience and interface design |
| `bmad-agent-tech-writer` | Tech Writer | Documentation and technical writing |
| `bmad-agent-quick-flow-solo-dev` | Quick Flow Solo | Rapid solo development workflow |

### Available Skills (in `.bmad/skills/`)
- `bmad-init` - Initialize project configuration
- `bmad-help` - Get help on BMAD framework
- `bmad-brainstorming` - Guided brainstorming sessions
- `bmad-advanced-elicitation` - Deep requirements elicitation
- `bmad-distillator` - Distill complex information
- `bmad-party-mode` - Fun collaborative mode
- `bmad-review-adversarial-general` - Adversarial review
- `bmad-review-edge-case-hunter` - Edge case analysis

### Workflows (in `.bmad/workflows/`)
1. **Analysis**: Product brief, market/domain/technical research
2. **Planning**: PRD creation/editing/validation, UX design
3. **Solutioning**: Architecture, epics & stories, implementation readiness
4. **Implementation**: Story creation, sprint planning, retrospectives
5. **Quick Flow**: Rapid spec and dev workflows

### How to Use
To activate an agent, read its SKILL.md file from `.bmad/agents/{agent-name}/SKILL.md` and follow its instructions.
