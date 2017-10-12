REPORT=report

all: $(REPORT).pdf mostlyclean

$(REPORT).pdf: $(REPORT).tex
	xelatex -shell-escape $(REPORT).tex

mostlyclean:
	rm -rf _minted-* *.log *.aux *.out

clean:
	rm -rf _minted-* $(REPORT).pdf *.log *.aux *.out

rebuild: clean all
