# =========================
# Utilities
# =========================
.PHONY: check clean rebuild

clean:
	$(DC) down -v
	rm -rf backend/vendor backend/node_modules
	rm -rf frontend/node_modules frontend/.next

rebuild: clean setup