REPORT=report

all: $(REPORT).pdf mostlyclean

$(REPORT).pdf: $(REPORT).tex
	pdflatex $(REPORT).tex

mostlyclean:
	rm -rf *.log *.aux *.out

clean:
	rm -rf $(REPORT).pdf *.log *.aux *.out

rebuild: clean all
